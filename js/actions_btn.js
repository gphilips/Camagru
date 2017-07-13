(function(){

	var miniature = document.getElementsByClassName('miniature'),
		del = document.getElementsByClassName('delete-mini'),
		likes = document.getElementsByClassName('like-mini'),
		active = document.getElementsByClassName('active'),
		comments = document.getElementsByClassName('comment-mini'),
		i = -1;
    
    while (++i < miniature.length)
    {
    	del[i].addEventListener('click', deletePicture);
    	del[i].addEventListener('mouseover', hoverDeleteBtn);
    	comments[i].addEventListener('mouseover', hoverCommentBtn);
	   	likes[i].addEventListener('mouseover', hoverLikeBtn);
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

	function hoverCommentBtn()
	{
		this.src = '/camagru/img/comments_active.png';
		this.addEventListener('mouseout', function()
		{
			this.src = '/camagru/img/comments.png';	
		});
	}
})();