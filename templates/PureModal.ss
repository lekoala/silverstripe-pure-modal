<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
<% base_tag %>
<title>$Title</title>
</head>

<%-- note that data-graphql-legacy matters in order to avoid graphql errors, see https://github.com/silverstripe/silverstripe-admin/issues/1231 --%>
<body class="cms cms-dialog $BaseCSSClasses" lang="$Locale.RFC1766" <% if $GraphQLLegacy %>data-graphql-legacy="1"<% end_if %>>
	<div class="cms-dialog-content">
		$Content
		$Form
	</div>
</body>
</html>
