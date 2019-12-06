var doc = new jsPDF()
var name = $("#name").html();
$('body').on('click','#btn-download-pdf', function(){
	doc.fromHTML($('#reg-info').html(), 10, 10, {
		width: '100px'
    });
	doc.save(name + '.pdf')
});