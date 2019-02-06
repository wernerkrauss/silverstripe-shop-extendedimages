<% require javascript(markguinn/silverstripe-shop-extendedimages:thirdparty/jquery/jquery.min.js) %>
<% require javascript(markguinn/silverstripe-shop-extendedimages:thirdparty/elevatezoom/jquery.elevatezoom.js) %>
<% require javascript(markguinn/silverstripe-shop-extendedimages:javascript/ProductImages.js) %>
<% require css(markguinn/silverstripe-shop-extendedimages:css/ProductImages.css) %>

<div id="ProductImageWrapper">
	<% if $Image.ContentImage %>
		<img id="MainProductImage" class="productImage" src="$Image.ContentImage.URL" data-zoom-image="$Image.LargeImage.URL" />
	<% else %>
		<img id="MainProductImage" class="productImage" src="http://placehold.it/300x200" />
	<% end_if %>
</div>

<% if $SortedAdditionalImages.Count %>
	<div id="ProductImageGallery">
		<% if $Image %>
			<a href="javascript:;" data-image="$Image.ContentImage.URL" data-zoom-image="$Image.LargeImage.URL" class="active"><img src="$Image.Thumbnail.URL" /></a>
		<% end_if %>
		<% loop $SortedAdditionalImages %>
			<a href="javascript:;" data-image="$ContentImage.URL" data-zoom-image="$LargeImage.URL"><img src="$Thumbnail.URL" /></a>
		<% end_loop %>
	</div>
<% end_if %>
