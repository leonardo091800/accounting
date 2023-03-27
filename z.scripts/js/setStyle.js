<script>
function setStyle(IDorCLASS, value, styleName, styleValue) {
	switch(IDorCLASS) {
	case 'id':
		setStyleID(value, styleName, styleValue);
		break;
	case 'class':
		setStyleClass(value, styleName, styleValue);
		break;
	default:
		alert('no options in js function setStyle(IDorCLASS, value, styleName, styleValue)');
	}
}
function setStyleID(idValue, styleName, styleValue) {
	var el = document.getElementById(idValue);
	el.setAttribute('style', styleName+' : '+styleValue);
}
</script>

