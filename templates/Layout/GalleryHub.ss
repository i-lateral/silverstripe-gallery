<% if $ShowSideBar && $Menu(2).exists %>
	<% include SideBar %>
<% end_if %>

<div class="content-container unit <% if $ShowSideBar && $Menu(2).exists %>col-md-9 size3of4 lastUnit<% end_if %>">
	<article>
		<h1>$Title</h1>
		<div class="content">$Content</div>
	</article>

	<% if $PaginatedChildren.exists %>
		<div class="row line">
			<% loop $PaginatedChildren %>
				<div class="unit size1of4 col-lg-2 col-md-3 col-xs-6 <% if $MultipleOf(4) %>lastUnit<% end_if %>">
					<p>
						<a href="$Link">
							$SortedImages.First.CroppedImage(150,150)<br/>
							$Title
						</a>
					</p>
				</div>
			<% end_loop %>
		</div>

        <% with $PaginatedChildren %>
            <% include Pagination %>
        <% end_with %>
	<% end_if %>
</div>