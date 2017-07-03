(function() {
    var snap = document.querySelector('#snap')
    	cam = document.querySelector('#webcam'),
    	canvas = document.querySelector('#canvas'),
    	take = document.querySelector('#take'),
    	closeIconCreated = 0,
    	saveIconCreated = 0;

    take.addEventListener('click', takePicture);

	navigator.getMedia = (navigator.getUserMedia ||
                        navigator.webkitGetUserMedia ||
                        navigator.mozGetUserMedia ||
                        navigator.msGetUserMedia);

	navigator.getMedia(
		{
		  video: true,
		  audio: false
		},
		function(stream)
		{
			if (navigator.mozGetUserMedia)
				cam.mozSrcObject = stream;
			else
			{
				var vendorURL = (window.URL || window.webkitURL);
				cam.src = vendorURL.createObjectURL(stream);
			}
			cam.play();
		},
		function(err)
		{
		  console.log("An error occured! " + err);
		}
	);

	function clearPicture()
	{
		canvas.setAttribute('width', 0);
		canvas.setAttribute('height', 0);
		snap.removeChild(closeIcon);
		snap.removeChild(saveIcon);
		closeIconCreated = 0;
		saveIconCreated = 0;
	}

	function createCloseIcon()
	{
		var closeIcon = document.createElement('img');
		closeIcon.src = 'img/close.png';
		closeIcon.id = 'closeIcon';
		return closeIcon;
	}

	function createSaveIcon()
	{
		var saveIcon = document.createElement('img');
		saveIcon.src = 'img/save.png';
		saveIcon.id = 'saveIcon';
		return saveIcon;
	}

	function takePicture()
	{
		canvas.width = cam.offsetWidth;
		canvas.height = cam.offsetHeight;
		canvas.getContext('2d').drawImage(cam, 0, 0, cam.offsetWidth, cam.offsetHeight);
		if (closeIconCreated == 0 && saveIconCreated == 0)
		{
			closeIcon = createCloseIcon();
			saveIcon = createSaveIcon();
			snap.appendChild(closeIcon);
			snap.appendChild(saveIcon);
			closeIconCreated = 1;
			saveIconCreated = 1;
			closeIcon.addEventListener('click', clearPicture);
			saveIcon.addEventListener('click', function(){
				var data = canvas.toDataURL('image/png');
				document.body.innerHTML = '<form id="savePicture" action="members/redirect_account.php" method="POST"><input type="hidden" name="imageTaken" value="'+data+'"></form>';
        		document.getElementById('savePicture').submit();
				clearPicture();
			});
		}
	}
})();