jQuery(function(){
});



addContact = function (){
    if ($('#table-contacts tbody .empty-table').length > 0) {
        $('#table-contacts tbody .empty-table').parent().remove();
    }
    let row = `<tr>
                    <td><input type="text" name="contact_name[]" class="form-control" required/></td>
                    <td><input type="text" name="contact_phone_number[]" required class="form-control"/></td>
                    <td><input type="text" name="contact_email[]" class="form-control"/></td>
                    <td><input type="text" name="contact_position[]" class="form-control"/></td>
                    <td><button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)"><i class="fas fa-trash"></i></button></td>
               </tr>`;
    $('#table-contacts tbody').append(row);
}

removeRow = function(element){
    $(element).parent().parent().remove();
}
