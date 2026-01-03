        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- Summernote JS -->
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
<script>
    $(document).ready(function() {
        $('.summernote').summernote({
            height: 400,
            toolbar: [
                ['style', ['style', 'bold', 'italic', 'underline', 'clear']],
                ['font', ['strikethrough', 'superscript', 'subscript', 'fontname', 'fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph', 'height']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video', 'hr']],
                ['layout', ['twoColumns', 'threeColumns', 'fourColumns', 'imageLeft', 'imageRight', 'alertBox']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ],
            buttons: {
                imageLeft: function(context) {
                    var ui = $.summernote.ui;
                    var button = ui.button({
                        contents: '<span class="fa fa-align-left"></span> Img Left',
                        tooltip: 'Insert Image Left Layout',
                        click: function() {
                            context.invoke('editor.pasteHTML', '<div class="row mb-4 align-items-center"><div class="col-md-4"><img src="https://via.placeholder.com/400x300" class="img-fluid rounded shadow-sm"></div><div class="col-md-8"><h3>Heading</h3><p>Enter text here...</p></div></div><p><br></p>');
                        }
                    });
                    return button.render();
                },
                imageRight: function(context) {
                    var ui = $.summernote.ui;
                    var button = ui.button({
                        contents: '<span class="fa fa-align-right"></span> Img Right',
                        tooltip: 'Insert Image Right Layout',
                        click: function() {
                            context.invoke('editor.pasteHTML', '<div class="row mb-4 align-items-center"><div class="col-md-8"><h3>Heading</h3><p>Enter text here...</p></div><div class="col-md-4"><img src="https://via.placeholder.com/400x300" class="img-fluid rounded shadow-sm"></div></div><p><br></p>');
                        }
                    });
                    return button.render();
                },
                twoColumns: function(context) {
                    var ui = $.summernote.ui;
                    var button = ui.button({
                        contents: '<span class="fa fa-columns"></span> 2 Col',
                        tooltip: 'Insert 2 Columns',
                        click: function() {
                            context.invoke('editor.pasteHTML', '<div class="row"><div class="col-md-6"><p>Column 1</p></div><div class="col-md-6"><p>Column 2</p></div></div><p><br></p>');
                        }
                    });
                    return button.render();
                },
                threeColumns: function(context) {
                    var ui = $.summernote.ui;
                    var button = ui.button({
                        contents: '<span class="fa fa-columns"></span> 3 Col',
                        tooltip: 'Insert 3 Columns',
                        click: function() {
                            context.invoke('editor.pasteHTML', '<div class="row"><div class="col-md-4"><p>Column 1</p></div><div class="col-md-4"><p>Column 2</p></div><div class="col-md-4"><p>Column 3</p></div></div><p><br></p>');
                        }
                    });
                    return button.render();
                },
                fourColumns: function(context) {
                    var ui = $.summernote.ui;
                    var button = ui.button({
                        contents: '<span class="fa fa-columns"></span> 4 Col',
                        tooltip: 'Insert 4 Columns',
                        click: function() {
                            context.invoke('editor.pasteHTML', '<div class="row"><div class="col-md-3"><p>Column 1</p></div><div class="col-md-3"><p>Column 2</p></div><div class="col-md-3"><p>Column 3</p></div><div class="col-md-3"><p>Column 4</p></div></div><p><br></p>');
                        }
                    });
                    return button.render();
                },
                alertBox: function(context) {
                    var ui = $.summernote.ui;
                    var button = ui.button({
                        contents: '<span class="fa fa-exclamation-triangle"></span> Alert',
                        tooltip: 'Insert Alert Box',
                        click: function() {
                            context.invoke('editor.pasteHTML', '<div class="alert alert-info"><h4 class="alert-heading">Notice</h4><p>Enter important content here.</p></div><p><br></p>');
                        }
                    });
                    return button.render();
                }
            },
            fontNames: ['Arial', 'Arial Black', 'Comic Sans MS', 'Courier New', 'Merriweather', 'Open Sans', 'Helvetica', 'Impact', 'Tahoma', 'Times New Roman', 'Verdana'],
            fontSizes: ['8', '9', '10', '11', '12', '14', '16', '18', '20', '22', '24', '28', '32', '36', '48', '64', '82', '150']
        });
    });
</script>
</body>
</html>
