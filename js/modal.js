(function() {

	if (document.getElementById('overlay'))
	{
		var overlay = document.getElementById('overlay'),
			modal = document.getElementById('modal');

		modal.addEventListener('click', dontLeave);
		overlay.addEventListener('click', quitModal);
	}

	function dontLeave(e)
	{
		e.stopPropagation();
	}

	function quitModal()
	{
		window.location.replace('gallery.php');
	}

})();