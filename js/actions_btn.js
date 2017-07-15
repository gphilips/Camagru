(function(){

	var miniature = document.getElementsByClassName('miniature'),
		del = document.getElementsByClassName('delete-mini'),
		likes = document.getElementsByClassName('like-mini'),
		active = document.getElementsByClassName('active'),
		i = -1;
    
    while (++i < miniature.length)
    {
    	del[i].addEventListener('click', deletePicture);
    	del[i].addEventListener('mouseover', hoverDeleteBtn);
	   	likes[i].addEventListener('mouseover', hoverLikeBtn);
	}

	if (document.getElementsByClassName('deleteCom-mini'))
	{
		var comments = document.getElementsByClassName('words'),
			delCom = document.getElementsByClassName('deleteCom-mini');

		i = -1;
		while (++i < comments.length)
			delCom[i].addEventListener('click', deleteComment);
	}

	function deletePicture()
	{
		if (confirm('Are you sure you want to delete this picture ?'))
		{
			var actions = this.parentElement,
				miniature = actions.previousElementSibling;
			this.innerHTML = '<form id="deletePicture" action="scripts/actions.php" method="POST"><input type="hidden" name="imageDelete" value="'+miniature.id+'"></form>';
        	document.getElementById('deletePicture').submit();
		}
	}

	function deleteComment()
	{
		if (confirm('Are you sure you want to delete this comment ?'))
		{
			var comment = this.nextElementSibling.querySelector('p');
			this.innerHTML = '<form id="deleteComment" action="scripts/actions.php" method="POST"><input type="hidden" name="commentDelete" value="'+comment.id+'"></form>';
        	document.getElementById('deleteComment').submit();
		}
	}

	function hoverDeleteBtn()
	{
		this.src = '/camagru/img/delete_active.png';
		this.addEventListener('mouseout', function()
		{
			this.src = '/camagru/img/delete.png';
		});
	}

	function hoverLikeBtn()
	{
		if (!this.classList.contains('active'))
		{
			this.src = '/camagru/img/like_active.png';
			this.addEventListener('mouseout', function()
			{
				this.src = '/camagru/img/like_inactive.png';
			});
		}
		else
		{
			this.addEventListener('mouseover', function()
			{
				this.src = '/camagru/img/like_inactive.png';
				this.addEventListener('mouseout', function()
				{
					this.src = '/camagru/img/like_active.png';
				});
			});

		}
	}
})();