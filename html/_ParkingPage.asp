<%TmpDomURL=Request.ServerVariables("SERVER_NAME")%>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
    <title><%=TmpDomURL%></title>
<style type="text/css">
	body { background-color: #fff; margin: 0px;}
    td { text-align:center; color: #333; font-family: segoe ui, verdana, tahoma, helvetica, sans-serif; font-size:40px; font-weight:bold;}
</style>
</head>
<body>
	<table style="width:100%; height:100%; margin:0px">
		<tr>
			<td style="vertical-align:bottom;">
			<img alt="Página Temporária" src="https://www.meuhost.net/paginaparqueada.jpg" style="height:187px; width:500px"></td>
		</tr>
		<tr>
			<td style="vertical-align:top; background-color:#eee"><br><%=TmpDomURL%><br></td>
		</tr>
	</table>
</body>
</html>