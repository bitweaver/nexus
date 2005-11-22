<?php
/**
* Nexus base class
*
* @abstract
* @author   xing <xing@synapse.plus.com>
* @version  $Revision: 1.1.1.1.2.12 $
* @package  nexus
*/

/**
* required setup
*/
require_once( NEXUS_PKG_PATH.'NexusSystem.php' );

/**
* @package  nexus
* @subpackage  Nexus
*/
class Nexus extends NexusSystem {
	/**
	* Primary key for the menu
	*/
	var $mMenuId;

	/**
	* Initialisation of this class
	*/
	function Nexus( $pMenuId=NULL, $pAutoLoad=TRUE ) {
		NexusSystem::NexusSystem();
		$this->mMenuId = $pMenuId;
		if ( $pAutoLoad ) {
			$this->load();
		}
		// if the cache folder doesn't exist yet, create item
		if( !is_dir( TEMP_PKG_PATH.'nexus' ) ) {
			mkdir( TEMP_PKG_PATH.'nexus', 0777 );
			mkdir( TEMP_PKG_PATH.'nexus/modules', 0777 );
		}
	}

	/**
	* Load the menu
	*/
	function load() {
		if( $this->mMenuId ) {
			$this->mInfo = $this->getMenu( $this->mMenuId );
			$this->mInfo['items'] = $this->getItemList( $this->mMenuId );
			$this->mInfo['tree'] = $this->createMenuTree( $this->mInfo['items'] );
		}
		return( count( $this->mInfo ) );
	}

	/**
	* Get menu information from database
	* @param $pMenuId menu id of the menu we want information from
	*/
	function getMenu( $pMenuId=NULL ) {
		if( !$pMenuId && $this->isValid() ) {
			$pMenuId = $this->mMenuId;
		}
		$bindVars = array();
		$query = 'SELECT tnm.* FROM `'.BIT_DB_PREFIX.'tiki_nexus_menus` tnm';
		if( is_numeric( $pMenuId ) ) {
			$query .= ' WHERE tnm.`menu_id`=?';
			$bindVars = array( $pMenuId );
		}
		if( $result = $this->mDb->query( $query, array( $bindVars ) ) ) {
			$ret = $result->fields;
		}
		return $ret;
	}

	/**
	* Get menu information from database
	* @param $pMenuId menu id of the menu we want information from
	*/
	function getMenuList( $pFindString=NULL, $pSortMode=NULL, $pOffset=NULL, $pMaxRows=NULL ) {
		$bindVars = array();
		$mid = '';
		if( $pFindString ) {
			$mid .= " WHERE UPPER(tnm.`title`) LIKE ? ";
			$bindVars[] = '%'.strtoupper( $pFindString ).'%';
		}
		if( $pSortMode ) {
			$mid .= " ORDER BY ".$this->mDb->convert_sortmode($pSortMode)." ";
		}

		$query = 'SELECT tnm.`menu_id` FROM `'.BIT_DB_PREFIX.'tiki_nexus_menus` tnm'.$mid;
		if( $pMaxRows && is_numeric( $pMaxRows ) ) {
		  $result = $this->mDb->query( $query, $bindVars, $pOffset, $pMaxRows );
		} else {
		  $result = $this->mDb->query( $query, $bindVars );
		}
		$menuIds = $result->getRows();
		$menus = array();
		foreach( $menuIds as $menuId ) {
			$tmpMenu = new Nexus( $menuId['menu_id'] );
			$menus[] = $tmpMenu->mInfo;
		}

		return $menus;
	}

	/**
	* Create usable menu tree
	* @param $pMenuHash full menu as supplied by '$this->getItemList( $pMenuId );'
	* @param $pStripped if set to true, removes all permissions, user isn't part of
	* @return menu with all menu items sorted with first and last items of each 'level' marked. items that contain siblings are marked with 'head' = TRUE;
	*/
	function createMenuTree( $pMenuHash, $pStripped=FALSE, $parent_id=0 ) {
		$ret = array();
		if( $pStripped && $parent_id == 0 ) {
			$pMenuHash = $this->checkUserPermission( $pMenuHash );
		}
		// get all child menu items for this item_id
		$children = $this->getChildItems( $pMenuHash, $parent_id );
		$pos = 1;
		$row_max = count( $children );
		foreach( $children as $item ) {
			$aux = $item;
			$aux['first']       = ( $pos == 1 );
			$aux['last']        = FALSE;
			$aux['head']        = FALSE;
			$ret[] = $aux;
			//Recursively add any children
			$subs = $this->createMenuTree( $pMenuHash, $pStripped, $item['item_id'] );
			if( !empty( $subs ) ) {
				// mark items that have children
				$row_last = count( $ret );
				$ret[$row_last - 1]['head'] = TRUE;
				$ret = array_merge( $ret, $subs );
			}
			if( $pos == $row_max ) {
				if( !empty( $item['parent_id'] ) ) {
					$tmpItem = $this->getItemList( NULL, $item['parent_id'] );
					$aux = $tmpItem[$item['parent_id']];
				} else {
					$aux['item_id'] = $item['item_id'];
				}
				$aux['first'] = FALSE;
				$aux['last']  = TRUE;
				$ret[] = $aux;
			}
			$pos++;
		}
		return $ret;
	}

	/**
	* Strip out all items a user doesn't have permission to view
	* @param $pMenuHash full menu as supplied by '$this->getItemList( $pMenuId );'
	* @return menu containing only items user is allowed to view
	*/
	function checkUserPermission( $pMenuHash ) {
		global $gBitUser;
		$ret = array();
		foreach( $pMenuHash as $item ) {
			if( !empty( $item['perm'] ) ) {
				if( $gBitUser->hasPermission( $item['perm'] ) ) {
					$ret[] = $item;
				}
			} else {
				$ret[] = $item;
			}
		}
		return $ret;
	}

	/**
	* Get all items in $pMenuHash that have a given parent_id
	* @param $pMenuHash full menu as supplied by '$this->getItemList( $pMenuId );'
	* @return array of items with a given parent_id
	*/
	function getChildItems( $pMenuHash, $parent_id=0 ) {
		$ret = array();
		foreach( $pMenuHash as $item ) {
			if( $item['parent_id'] == $parent_id ) {
				$ret[] = $item;
			}
		}
		return $ret;
	}

	/**
	* Validate that a menu is being loaded and present
	* @return TRUE if all is ok
	*/
	function isValid() {
		return( !empty( $this->mMenuId ) );
	}

	/**
	* Check if all required items are present for menu creation / insertion
	* @return number of errors encountered
	*/
	function verifyMenu( &$pParamHash ) {
		if( empty( $pParamHash['title'] ) ) {
			$this->mErrors['error'] = 'Could not store menu because no title was given.';
		}
		if( empty( $pParamHash['description'] ) ) {
			$pParamHash['description'] = NULL;
		}
		// set the default plugin_guid to suckerfish menus
		if( empty( $pParamHash['plugin_guid'] ) ) {
			$pParamHash['plugin_guid'] = NEXUS_PLUGIN_GUID_SUCKERFISH;
		}
		$type_name = 'type_' . $pParamHash['plugin_guid'];
		if ( empty( $pParamHash[$type_name] )) {
			$pParamHash['type'] = 'nor';
		}
		else {
			$pParamHash['type'] = $pParamHash[$type_name];
		}
		if( empty( $pParamHash['editable'][0] ) || !is_numeric( $pParamHash['editable'][0] ) ) {
			$pParamHash['editable'][0] = 0;
		}
		$pParamHash['editable'] = $pParamHash['editable'][0];
		return( count( $this->mErrors ) == 0 );
	}

	/**
	* Store menu in db
	* @param menu_id if set, will update given menu - if not set, we create a new entry in the db
	* @param title title of menu
	* @param description description of menu
	* @param type type of menu
	* @param editable if menu is editable by other users - takes 0 or 1
	* @return new menu menu_id or FALSE if not created
	*/
	function storeMenu( &$pParamHash ) {
		$ret = FALSE;
		if( $this->verifyMenu( $pParamHash ) ) {
			if( empty( $pParamHash['menu_id'] ) ) {
				$query = "INSERT INTO `".BIT_DB_PREFIX."tiki_nexus_menus`( `title`,`description`,`type`,`plugin_guid`,`editable` ) VALUES(?,?,?,?,?)";
				$result = $this->mDb->query( $query, array( $pParamHash['title'], $pParamHash['description'], $pParamHash['type'], $pParamHash['plugin_guid'], $pParamHash['editable'] ) );
				$query = "SELECT MAX(`menu_id`) FROM `".BIT_DB_PREFIX."tiki_nexus_menus`";
				$ret = $this->mDb->getOne( $query, array() );
			} else {
				$query = "UPDATE `".BIT_DB_PREFIX."tiki_nexus_menus` SET `title`=?,`description`=?,`type`=?,`plugin_guid`=?,`editable`=? WHERE `".BIT_DB_PREFIX."tiki_nexus_menus`.`menu_id`=?";
				$result = $this->mDb->query( $query, array( $pParamHash['title'], $pParamHash['description'], $pParamHash['type'], $pParamHash['plugin_guid'], $pParamHash['editable'], $pParamHash['menu_id'] ) );
				$ret = $pParamHash['menu_id'];
			}
			$this->writeModuleCache( $ret );
			$this->writeMsieJs();
		} else {
			vd( $this->mErrors );
		}
		return $ret;
	}

	/**
	* Delete menu and associated menu items from db
	* @return number of errors encountered
	*/
	function expungeMenu( $pMenuId ) {
		// delete menu items
		$query = "DELETE FROM `".BIT_DB_PREFIX."tiki_nexus_menu_items` WHERE `menu_id`=?";
		$this->mDb->query( $query, array( $pMenuId ) );

		// delete menu
		$query = "DELETE FROM `".BIT_DB_PREFIX."tiki_nexus_menus` WHERE `menu_id`=?";
		$this->mDb->query( $query, array( $pMenuId ) );

		// rewrite the entire cache, just to make sure everything is in order
		$this->rewriteMenuCache();

		return( count( $this->mErrors ) == 0 );
	}

	/**
	* Get menu items from the database
	* @param $pItemId ID of menu item to get. if set, we only get this item.
	* @param $pMenuId ID of menu to get
	* @return all menu items with a given menu ID
	*/
	function getItemList( $pMenuId=NULL, $pItemId=NULL ) {
		$bindVars = array();
		$ret = array();
		$query = 'SELECT tnmi.* FROM `'.BIT_DB_PREFIX.'tiki_nexus_menu_items` tnmi';
		if( is_numeric( $pItemId ) ) {
			$query .= ' WHERE tnmi.`item_id`=?';
			$bindVars = array( $pItemId );
		} elseif( is_numeric( $pMenuId ) ) {
			$query .= ' WHERE tnmi.`menu_id`=?';
			$bindVars = array( $pMenuId );
		}
		$query .= ' ORDER BY tnmi.`pos`';
		$result = $this->mDb->query( $query,array( $bindVars ) );
		while( !$result->EOF ) {
			$item = $result->fields;
			$item['display_url'] = $this->printUrl( $item );
			$ret[$item['item_id']] = $item;
			$result->MoveNext();
		}
		// this version of the loop inserts the submenu at the point of choice
		foreach( $ret as $item ) {
			if( $item['rsrc_type'] == 'menu_id' ) {
				$tmp = $this->getItemList( $item['rsrc'] );
				foreach( $tmp as $i ) {
					if( $i['parent_id'] == 0 ) {
						$tmp[$i['item_id']]['parent_id'] = $item['item_id'];
					}
					// pass all items on to ret
					$ret[$i['item_id']] = $tmp[$i['item_id']];
				}
			}
		}
		return $ret;
	}

	/**
	* Create the correct url for a given item
	* @param $pItemHash complete item hash
	* @return url
	*/
	function printUrl( $pItemHash ) {
		$ret = NULL;
		if( isset( $pItemHash['rsrc'] ) && isset( $pItemHash['rsrc_type'] ) ) {
			switch( $pItemHash['rsrc_type'] ) {
				case 'external':
				case 'internal':
					$ret .= $pItemHash['rsrc'];
					break;
				case 'content_id':
					$ret .= BIT_ROOT_URL.'index.php?content_id='.$pItemHash['rsrc'];
					break;
				case 'structure_id':
					$ret .= BIT_ROOT_URL.'index.php?structure_id='.$pItemHash['rsrc'];
					break;
			}
		}
		return $ret;
	}

	/**
	* Verify rsrc handed to us and specify what type it is
	* @return fixed rsrc and corresponding rsrc_type
	*/
	function verifyRsrc( &$pParamHash ) {
		$tiki_root_pattern = "/^".preg_replace( "/\//", "\/", BIT_ROOT_URL )."/i";
		if( preg_match( "/^(http:\/\/)/i", $pParamHash['rsrc'] ) ) {
			$pParamHash['rsrc_type'] = 'external';
		} elseif( is_numeric( $pParamHash['rsrc'] ) ) {
			// if the resource type is numeric but we don't know what type it is, assume that it's a content_id
			if( !isset( $pParamHash['rsrc_type'] ) ) {
				$pParamHash['rsrc_type'] = 'content_id';
			}
		} elseif( preg_match( $tiki_root_pattern, $pParamHash['rsrc'] ) ) {
			// if BIT_ROOT_URL can be found at the beginning of the string, assume it's an internal link
			// in most cases this will only be a '/' but hopefully that's enough
			$pParamHash['rsrc_type'] = 'internal';
		} else {
			// if we haven't caught this resource yet, we just prepend it with BIT_ROOT_URL and hope for the best
			$pParamHash['rsrc'] = BIT_ROOT_URL.$pParamHash['rsrc'];
			$pParamHash['rsrc_type'] = 'internal';
		}
	}

	/**
	* Verify if a given menu item contains all required information and prepare menu_items table for item insertion.
	* @return number of errors encountered
	*/
	function verifyItem( &$pParamHash ) {
		if( empty( $pParamHash['hint'] ) )		{ $pParamHash['hint'] = NULL; }
		if( empty( $pParamHash['perm'] ) )		{ $pParamHash['perm'] = NULL; }
		if( empty( $pParamHash['title'] ) ) {
			$this->mErrors['error'] = 'Could not store menu item. No item title was given.';
		}
		if( empty( $pParamHash['menu_id'] ) || !is_numeric( $pParamHash['menu_id'] ) ) {
			$this->mErrors['error'] = 'Could not store menu item. Invalid menu id. Menu id: '.$pParamHash['menu_id'];
		} else {
			$this->mDb->StartTrans();
			if( empty( $pParamHash['parent_id'] ) || !is_numeric( $pParamHash['parent_id'] ) ) {
				$pParamHash['parent_id'] = 0;
				// if no parent_id is not known, but we have an after_ref_id, we use that to work out the parent_id
				if( !empty( $pParamHash['after_ref_id'] ) ) {
					$pParamHash['parent_id'] = $this->mDb->getOne("SELECT `parent_id` FROM `".BIT_DB_PREFIX."tiki_nexus_menu_items` WHERE `item_id`=?", array( (int)$pParamHash['after_ref_id'] ) );
				}
			}
			$pParamHash['max'] = 0;
			if( !empty( $pParamHash['after_ref_id'] ) && is_numeric( $pParamHash['after_ref_id'] ) ) {
				$pParamHash['max'] = $this->mDb->getOne("SELECT `pos` FROM `".BIT_DB_PREFIX."tiki_nexus_menu_items` WHERE `item_id`=?", array( (int)$pParamHash['after_ref_id'] ) );
				if( $pParamHash['max'] > 0 ) {
					//If max is 5 then we are inserting after position 5 so we'll insert 5 and move all the others
					$query = "UPDATE `".BIT_DB_PREFIX."tiki_nexus_menu_items` SET `pos`=`pos`+1 WHERE `pos`>? AND `parent_id`=?";
					$result = $this->mDb->query( $query, array( (int)$pParamHash['max'], (int)$pParamHash['parent_id'] ) );
				}
			}
			$this->mDb->CompleteTrans();
			$pParamHash['max']++;
			// if we get passed the position of where the item is to go, we pass it to 'max' -- used for importStructure()
			if( !empty( $pParamHash['pos'] ) && is_numeric( $pParamHash['pos'] ) && empty( $pParamHash['after_ref_id'] ) ) {
				$pParamHash['max'] = $pParamHash['pos'];
			}
			// work out what type of rsrc was passed in and fix
			if( !empty( $pParamHash['rsrc'] ) ) {
				$this->verifyRsrc( $pParamHash );
			} else {
				$pParamHash['rsrc'] = NULL;
				$pParamHash['rsrc_type'] = NULL;
			}
		}
		return( count( $this->mErrors ) == 0 );
	}

	/**
	* Create a menu item entry
	* @param parent_id The parent entry to add this to. If NULL, create new structure.
	* @param after_ref_id The entry to add this one after. If NULL, put it in position 0.
	* @param title The wiki page to reference
	* @return the new entries item_id or FALSE if not created.
	*/
	function storeItem( &$pParamHash ) {
		$ret = FALSE;
		if ( $this->verifyItem( $pParamHash ) ) {
			$this->mDb->StartTrans();
			if( empty( $pParamHash['item_id'] ) ) {
				$query = "INSERT INTO `".BIT_DB_PREFIX."tiki_nexus_menu_items`( `menu_id`,`parent_id`,`pos`,`title`,`hint`,`rsrc`,`rsrc_type`,`perm` ) VALUES( ?,?,?,?,?,?,?,? )";
				$result = $this->mDb->query( $query, array( (int)$pParamHash['menu_id'], (int)$pParamHash['parent_id'], (int)$pParamHash['max'], $pParamHash['title'], $pParamHash['hint'], $pParamHash['rsrc'], $pParamHash['rsrc_type'], $pParamHash['perm'] ) );
				$query = "SELECT MAX(`item_id`) FROM `".BIT_DB_PREFIX."tiki_nexus_menu_items`";
				$ret = $this->mDb->getOne( $query, array() );
			} else {
				$query = "UPDATE `".BIT_DB_PREFIX."tiki_nexus_menu_items` SET `title`=?, `hint`=?, `rsrc`=?, `rsrc_type`=?, `perm`=? WHERE `item_id`=?";
				$result = $this->mDb->query( $query, array( $pParamHash['title'], $pParamHash['hint'], $pParamHash['rsrc'], $pParamHash['rsrc_type'], $pParamHash['perm'], $pParamHash['item_id'] ) );
				$ret = $pParamHash['item_id'];
			}
			$this->mDb->CompleteTrans();
			$this->rewriteMenuCache();
		} else {
			return( count( $this->mErrors ) == 0 );
		}
		return $ret;
	}

	/**
	* Delete item
	* @param $pItemId item to be removed
	* @return deleted item information
	*/
	function expungeItem( $pItemId=NULL, $pWriteCache=TRUE ) {
		if( isset( $pItemId ) && is_numeric( $pItemId ) ) {
			// get full information of item that we are removing
			$remItem = $this->getItemList( NULL, $pItemId );
			if( !empty( $remItem[$pItemId] ) ) {
				$remItem = $remItem[$pItemId];
				$this->mDb->StartTrans();
				// get all items that are on the same level
				$query = "SELECT * FROM `".BIT_DB_PREFIX."tiki_nexus_menu_items` WHERE `parent_id`=? ORDER BY `pos`";
				$result = $this->mDb->query( $query, array( $pItemId ) );
				// this value is needed to correclty position items that are moved up a level
				$pos_count = 0;
				// first we move children up one level
				while( !$result->EOF ) {
					$item = $result->fields;
					//Make a space for the item after its parent
					$query = "UPDATE `".BIT_DB_PREFIX."tiki_nexus_menu_items` SET `pos`=`pos`+1 WHERE `pos`>?+".$pos_count." AND `parent_id`=? AND `menu_id`=?";
					$res = $this->mDb->query( $query, array( (int)$remItem['pos'], (int)$remItem['parent_id'], (int)$remItem['menu_id'] ) );
					// increase insertion count here
					$pos_count++;
					// move item up one level
					$query = "UPDATE `".BIT_DB_PREFIX."tiki_nexus_menu_items` SET `parent_id`=?, `pos`=?+".$pos_count." WHERE `item_id`=?";
					$this->mDb->query( $query, array( (int)$remItem['parent_id'], (int)$remItem['pos'], (int)$item['item_id'] ) );
					$result->MoveNext();
				}
				// all items below remItem have to be shifted up by one
				$query = "UPDATE `".BIT_DB_PREFIX."tiki_nexus_menu_items` SET `pos`=`pos`-1 WHERE `pos`>? AND `parent_id`=? AND `menu_id`=?";
				$this->mDb->query( $query, array( (int)$remItem['pos'], (int)$remItem['parent_id'], (int)$remItem['menu_id'] ) );
				// finally, we are ready do delete remItem
				$query = "DELETE FROM `".BIT_DB_PREFIX."tiki_nexus_menu_items` WHERE `item_id`=?";
				$result = $this->mDb->query( $query, array( $pItemId ) );
				$this->mDb->CompleteTrans();
				return $remItem;
				if( $pWriteCache ) {
					$this->writeModuleCache( $remItem['menu_id'] );
				}
			}
		} else {
			$this->mErrors['error'] = "The menu item could not be removed because no valid item id was given";
			return FALSE;
		}
	}

	/**
	* Remove items to content that has been removed
	* @param $pMenuId menu from which we want to remove dead links
	* @return titles of links removed
	* @TODO check for items in structures. if they don't exist there, replace them with the appropriate content id
	*/
	function expungeDeadItems( $pMenuId=NULL ) {
		if( isset( $pMenuId ) && is_numeric( $pMenuId ) ) {
			// get $contentList
			include_once( LIBERTY_PKG_PATH.'get_content_list_inc.php' );
			foreach( $contentList['data'] as $contentItem ) {
				$contentIds[] = $contentItem['content_id'];
			}
			$deathList = FALSE;
			foreach( $this->mInfo['items'] as $item ) {
				if( $item['rsrc_type'] == 'content_id' && !in_array( $item['rsrc'], $contentIds ) ) {
					$this->expungeItem( $item['item_id'], FALSE );
					$deathList[] = $item['title'];
				}
			}
			$this->writeModuleCache( $pMenuId );
		}
		return $deathList;
	}

	/**
	* Move item west
	* @param $pItemId item id of item to be moved
	*/
	function moveItemWest( $pItemId=NULL ) {
		if( $this->isValid() && $pItemId ) {
			// pass current item into managable array
			$item = $this->mInfo["items"][$pItemId];
			// if there is a parent and the parent isnt the menu root item.
			if( $item['parent_id'] > 0 ) {
				$parentItem = $this->getItemList( $this->mMenuId, (int)$item["parent_id"] );
				$parentItem = $parentItem[$item["parent_id"]];
				$this->mDb->StartTrans();
				if( empty( $parentItem["parent_id"] ) ) {
					$max_row = $this->mDb->getOne("SELECT `pos` FROM `".BIT_DB_PREFIX."tiki_nexus_menu_items` WHERE `item_id`=?", array( $item['parent_id'] ) );
					$parent_item['pos'] = $max_row;
					$parent_item['parent_id'] = 0;
				}
				//Make a space for the item after its parent
				$query = "UPDATE `".BIT_DB_PREFIX."tiki_nexus_menu_items` SET `pos`=`pos`+1 WHERE `pos`>? AND `parent_id`=?";
				$this->mDb->query( $query, array( (int)$parentItem["pos"], (int)$parentItem["parent_id"] ) );
				//Move the item up one level
				$query = "UPDATE `".BIT_DB_PREFIX."tiki_nexus_menu_items` SET `parent_id`=?, `pos`=(? + 1) WHERE `item_id`=?";
				$this->mDb->query($query, array( (int)$parentItem["parent_id"], (int)$parentItem["pos"], $pItemId ) );
				$this->mDb->CompleteTrans();
				$this->writeModuleCache( $item['menu_id'] );
			}
		}
	}

	/**
	* Move item east
	* @param $pItemId item id of item to be moved
	*/
	function moveItemEast( $pItemId=NULL ) {
		if( $this->isValid() && $pItemId ) {
			// pass current item into managable array
			$item = $this->mInfo["items"][$pItemId];
			$this->mDb->StartTrans();
			$query = "SELECT `item_id`, `pos` FROM `".BIT_DB_PREFIX."tiki_nexus_menu_items` WHERE `pos`<? AND `parent_id`=? AND `menu_id`=? ORDER BY `pos` DESC";
			$result = $this->mDb->query( $query, array( (int)$item["pos"], (int)$item["parent_id"], (int)$item["menu_id"] ) );
			if( $previous = $result->fetchRow() ) {
				//Get last child item for previous sibling
				$query = "SELECT `pos` FROM `".BIT_DB_PREFIX."tiki_nexus_menu_items` WHERE `parent_id`=? AND `menu_id`=? ORDER BY `pos` DESC";
				$result = $this->mDb->query( $query, array( (int)$previous["item_id"], (int)$item["menu_id"] ) );
				if( $res = $result->fetchRow() ) {
					$pos = $res["pos"];
				} else {
					$pos = 0;
				}
				$query = "UPDATE `".BIT_DB_PREFIX."tiki_nexus_menu_items` SET `parent_id`=?, `pos`=(? + 1) WHERE `item_id`=?";
				$this->mDb->query( $query, array( (int)$previous["item_id"], (int)$pos, (int)$item["item_id"] ) );
				//Move items up below that had previous parent and pos
				$query = "UPDATE `".BIT_DB_PREFIX."tiki_nexus_menu_items` SET `pos`=`pos`-1 WHERE `pos`>? AND `parent_id`=? AND `menu_id`=?";
				$this->mDb->query( $query, array( $item["pos"], $item["parent_id"], $item["menu_id"] ) );
				$this->mDb->CompleteTrans();
				$this->writeModuleCache( $item['menu_id'] );
			}
		}
	}

	/**
	* Move item south
	* @param $pItemId item id of item to be moved
	*/
	function moveItemSouth( $pItemId=NULL ) {
		if( $this->isValid() && $pItemId ) {
			// pass current item into managable array
			$item = $this->mInfo["items"][$pItemId];
			$this->mDb->StartTrans();
			$query = "SELECT `item_id`, `pos` FROM `".BIT_DB_PREFIX."tiki_nexus_menu_items` WHERE `pos`>? AND `parent_id`=? ORDER BY `pos` ASC";
			$result = $this->mDb->query( $query, array( (int)$item["pos"], (int)$item["parent_id"] ) );
			if( $res = $result->fetchRow() ) {
				//Swap position values
				$query = "UPDATE `".BIT_DB_PREFIX."tiki_nexus_menu_items` SET `pos`=? WHERE `item_id`=?";
				$this->mDb->query( $query, array( (int)$item["pos"], (int)$res["item_id"] ) );
				$this->mDb->query( $query, array( (int)$res["pos"], (int)$item["item_id"] ) );
			}
			$this->mDb->CompleteTrans();
			$this->writeModuleCache( $item['menu_id'] );
		}
	}

	/**
	* Move item north
	* @param $pItemId item id of item to be moved
	*/
	function moveItemNorth( $pItemId=NULL ) {
		if( $this->isValid() && $pItemId ) {
			// pass current item into managable array
			$item = $this->mInfo["items"][$pItemId];
			$this->mDb->StartTrans();
			$query = "SELECT `item_id`, `pos` from `".BIT_DB_PREFIX."tiki_nexus_menu_items` WHERE `pos`<? AND `parent_id`=? ORDER BY `pos` desc";
			$result = $this->mDb->query( $query, array((int)$item["pos"], (int)$item["parent_id"] ) );
			if( $res = $result->fetchRow() ) {
				//Swap position values
				$query = "UPDATE `".BIT_DB_PREFIX."tiki_nexus_menu_items` SET `pos`=? WHERE `item_id`=?";
				$this->mDb->query( $query, array( (int)$res["pos"], (int)$item["item_id"] ) );
				$this->mDb->query( $query, array( (int)$item["pos"], (int)$res["item_id"] ) );
			}
			$this->mDb->CompleteTrans();
			$this->writeModuleCache( $item['menu_id'] );
		}
	}

	/**
	* Imports a structure from tiki_structures to nexus including hierarchy
	* @return number of errors encountered
	*/
	function importStructure( $pStructureId=NULL ) {
		if( $pStructureId || !is_numeric( $pStructureId ) ) {
			include_once( LIBERTY_PKG_PATH.'LibertyStructure.php');
			$structure = new LibertyStructure( $pStructureId );
			$structure->load();

			// order matters for these conditionals
			if( empty( $structure ) || !$structure->isValid() ) {
				$this->mErrors['structure'] = 'Invalid structure';
			}

			if( $structure->mInfo['root_structure_id'] == $structure->mInfo['structure_id'] ) {
				$rootStructure = &$structure;
			} else {
				$rootStructure = new LibertyStructure( $structure->mInfo['root_structure_id'] );
				$rootStructure->load();
			}
			$structureList = $rootStructure->getSubTree( $rootStructure->mInfo['structure_id'] );
			$menuHash['title'] = $rootStructure->mInfo['title'];
			if( $menu_id = $this->storeMenu( $menuHash ) ) {
				// we need to insert the structure title manually, as this is not part of the structure
				$itemHash = array(
					'menu_id' => $menu_id,
					'title' => $rootStructure->mInfo['title'],
					'pos' => 1,
					'parent_id' => 0,
					'rsrc' => $rootStructure->mInfo['structure_id'],
					'rsrc_type' => 'structure_id'
				);
				$storedItem = $this->storeItem( $itemHash );

				// insert all nodes in structure as menu items
				foreach( $structureList as $structureItem ) {
					if( $structureItem['first'] ) {
						// get id of the current item
						$query = "SELECT MAX(`item_id`) FROM `".BIT_DB_PREFIX."tiki_nexus_menu_items`";
						$parentPath[] = $this->mDb->getOne( $query, array() );
						$parent_id = end( $parentPath );
					}
					if( $structureItem['last'] ) {
						// move up one step in the structure parentPath
						array_pop( $parentPath );
						$parent_id = end( $parentPath );
					} else {
						// save the item in the menu
						$tmpItem = $rootStructure->getNode( $structureItem['structure_id'] );
						$itemHash = array(
							'menu_id' => $menu_id,
							'title' => $structureItem['title'],
							'pos' => $tmpItem['pos'],
							'parent_id' => $parent_id,
							'rsrc' => isset( $structureItem['structure_id'] ) ? $structureItem['structure_id'] : NULL,
							'rsrc_type' => 'structure_id'
						);
						$storedItem = $this->storeItem( $itemHash );
					}
				}
			} else {
				$this->mErrors['menu'] = 'The menu could not be stored.';
			}
		} else {
			$this->mErrors['structure'] = 'No valid structure id was given.';
		}
		return( count( $this->mErrors ) == 0 );
	}

	/**
	* rewrites all menu cache files. particularly important when menus have been renamed or deleted
	* @param $pMenuId menu id of the menu for which we want to create a cache file
	* @return number of errors encountered
	*/
	function rewriteMenuCache() {
		if( is_dir( $path = TEMP_PKG_PATH.'nexus/modules' ) ) {
			$handle = opendir( $path );
			while( false!== ( $cache_file = readdir( $handle ) ) ) {
				if( $cache_file != "." && $cache_file != ".." ) {
					unlink( $path.'/'.$cache_file );
				}
			}

			// get the menus and rewrite the cache, one by one
			$menuList = $this->getMenuList();
			if( !empty( $menuList ) ) {
				foreach( $menuList as $menu ) {
					$this->writeModuleCache( $menu['menu_id'] );
				}
			}

			// now that the menus have been rewritten, update the MSIE js file
			$this->writeMsieJs();
		} else {
			$this->mErrors['rewrite'][] = "The cache directory for nexus menus doesn't exist";
		}
		return( count( $this->mErrors ) == 0 );
	}

	/**
	* writes cache files to where the plugins determine
	* @param $pMenuId menu id of the menu for which we want to create a cache file
	* @return number of errors encountered
	*/
	function writeModuleCache( $pMenuId=NULL ) {
		if( $this->isValid() && !isset( $pMenuId ) ) {
			$pMenuId = $this->mInfo['menu_id'];
		}
		// load the menu to make sure we have the latest version
		$cacheMenu = new Nexus( $pMenuId );
		if( !empty( $cacheMenu->mInfo['plugin_guid'] ) ) {
			global $gNexusSystem;
			if( $func = $gNexusSystem->getPluginFunction( $cacheMenu->mInfo['plugin_guid'], 'write_cache_function' ) ) {
				$moduleCache = $func( $cacheMenu );
			}
		}
		if( !empty( $moduleCache ) ) {
			foreach( $moduleCache as $cache_file => $cache_string ) {
				$h = fopen( TEMP_PKG_PATH.'nexus/modules/'.$cache_file, 'w' );
				if( isset( $h ) ) {
			        fwrite( $h, $cache_string );
					fclose( $h );
				} else {
					$this->mErrors['error'][] = "Unable to write to ".realpath( $cache_file );
				}
			}
		} else {
			$this->mErrors['error'][] = "Unable to write the cache file because there was something wrong with the plugin: ".$cacheMenu->mInfo['plugin_guid'];
		}
		return( count( $this->mErrors ) == 0 );
	}

	/**
	* function to write a js file with an array of menus that require the hoverfix for MSIE
	* @return number of errors encountered
	*/
	function writeMsieJs() {
		global $gBitSystem;
		$menuList = $this->getMenuList();
		$jsMenuIds = array();
		foreach( $menuList as $menu ) {
			$jsMenuIds[] = $menu['menu_id'];
		}
		$cache_path = ( TEMP_PKG_PATH.'nexus/modules/hoverfix_array.js' );
		$cache_file = fopen( $cache_path, 'w' );
		if( isset( $cache_file ) ) {
			$fw = 'nexusMenus.push(';
			foreach( $jsMenuIds as $key => $menu_id ) {
				$fw .= ( $key == 0 ) ? '' : ',';
				$fw .= '"nexus'.$menu_id.'"';
			}
			$fw .= ');';
	        fwrite( $cache_file, $fw );
			fclose( $cache_file );
		} else {
			$this->mErrors['chachefile'] = "Unable to write to ".realpath( $cache_path );
		}
		return( count( $this->mErrors ) == 0 );
	}
	
	/**
	* This is not in use yet. would be good if this could be updated directly from the db
	*/
	function getGalleryListMenu( $pParentId=NULL ) {
		global $gBitSystem, $gFisheyeGallery;
		require_once( FISHEYE_PKG_PATH.'FisheyeGallery.php');
		
		$gFisheyeGallery = new FisheyeGallery();

		$hash['root_only'] = TRUE;
		$hash['get_thumbnails'] = FALSE;
		$galleryList = $gFisheyeGallery->getList( $hash );

		foreach( $galleryList as $key => $gal ) {
			$itemHash['item_id'] = 'gl'.$key;
			$itemHash['parent_id'] = $pParentId;
			$itemHash['title'] = $gal['title'];
			$itemHash['rsrc'] = $gal['content_id'];
			$itemHash['rsrc_type'] = 'content_id';
			$ret['gl'.$key] = $itemHash;
		}
		return $ret;
	}
}
?>
