AlertMsg = "Sorry, that\'\s not a valid page."

function MenuNavSelect(form,elt) {
  if (form != null) {
    if(form.elements[elt].selectedIndex.value == 0) {
      alert(AlertMsg);
      form.elements[elt].selected='true';
    } else {
      window.location = form.elements[elt].options[form.elements[elt].selectedIndex].value;
    }
  }
}
