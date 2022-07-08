$(function(){
	const $cartQuantity = $('#cart-quantity');
	const $addToCart = $('.btn-add-to-cart');
	const $itemQuantity = $('.item-quantity');

	$addToCart.click(ev=>
	{
		ev.preventDefault();
		const $this = $(ev.target);
		const id = $this.closest('.product-item').data('key');
		

		$.ajax({
			method: 'POST',
			url: $this.attr('href'),
			data : {id},
			success : function()
			{
				$cartQuantity.text(parseInt($cartQuantity.text() || 0 )+1);
			}
		})
	})

	$itemQuantity.change(ev=>{
		const $this = $(ev.target);
		let $tr = $this.closest('tr');
		id = $tr.data('id');
	
		$.ajax({
			method : 'post',
			url : $tr.data('url'),
			data : {id,quantity:$this.val()},
			success : function(totalQuantity)
			{		 totalQuantity = totalQuantity.replaceAll('"','');
				$cartQuantity.text(parseInt(totalQuantity));
			}
		})
	})
});