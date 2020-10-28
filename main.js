$(document).ready(function(){

	$('.ramka').on('click', function()
	{
		var ramka_num = $(this).data('ramka');
		$('.img img').removeClass().addClass('ramka_' + ramka_num);
		$('.ramki .ramka').removeClass('active');
		$(this).addClass('active');
	})

	ImageSelectCrop();

	$('.btn-crop').on('click', function(){
		ImageCrop();
	});

});

function ImageCrop()
{
	var idForm = $('#checkCoords');
	$(idForm).unbind();
	var data = $(idForm).serialize();

	$.ajax({
		method: 'POST',
		url: 'crop.php',
		data: data,
		success: function(res){
			console.log(res);
			$('.img').removeClass('img-vertical');
			if (res.orient == "vertical")
			{
				$('.img').addClass('img-vertical');
			}
			$('.img').html('<img src="'+ res.image +'" alt="img">');
			$('.ramki').removeClass('hidden');
		}
	});
	return false;
}

function ImageSelectCrop()
{
	$('#target').Jcrop({
		// aspectRatio: 1,
		minSize: [100, 100],
		onSelect: updateCoords,
		setSelect: [ 0, 0, 100, 100 ]
	});
}

function updateCoords(c)
{
	$('#x').val(c.x);
	$('#y').val(c.y);
	$('#w').val(c.w);
	$('#h').val(c.h);
};
