UPLOADCARE_LOCALE = "en";
UPLOADCARE_TABS = "file gdrive dropbox skydrive";
UPLOADCARE_PUBLIC_KEY = "750f0f71bc3b703af902";

function uploadcare_init(id) {
    var widget = uploadcare.Widget('#' + id);
    var extensions = function(fileInfo) {
        if (fileInfo.name === null) {
            return;
        }
        var ext = fileInfo.name.substring(fileInfo.name.lastIndexOf('.') + 1);
        var valid = ['docx', 'doc', 'rtf', 'txt', 'pdf'];
        if ($.inArray(ext, valid) == -1) {
            alert('Resume files can only be the following types: .DOCX, .DOC, .RTF, .TXT, .PDF');
            throw new Error('extensions');
        }
    };
    var size = function(fileInfo) {
        if (fileInfo.size === null) {
            return;
        }
        if (fileInfo.size > 1048576) {
            alert('The file is too large. Allowed maximum size is 1MB.');
            throw new Error('size');
        }
    };
    widget.validators.push(extensions);
    widget.validators.push(size);
}