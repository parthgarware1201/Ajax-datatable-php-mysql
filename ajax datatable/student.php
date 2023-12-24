<?php include('connection.php'); ?>
<!doctype html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap CSS -->
  <link href="css/bootstrap5.0.1.min.css" rel="stylesheet" crossorigin="anonymous">
  <link rel="stylesheet" type="text/css" href="css/datatables-1.10.25.min.css" />
  <title>Server Side CRUD Ajax Operations</title>
  <style type="text/css">
    .btnAdd {
      text-align: right;
      width: 83%;
      margin-bottom: 20px;
    }
  </style>
</head>

<body>
  <div class="container-fluid">
    <h2 class="text-center">Welcome to Datatable</h2>
    <p class="datatable design text-center">Welcome to Datatable</p>
    <div class="row">
      <div class="container">
        <div class="btnAdd">
          <a href="#" data-id="" id="addButton" data-bs-toggle="modal" data-bs-target="#addeditStudentModal" class="btn btn-success btn-sm">Add User</a>
        </div>
        <div class="row">
          <div class="col-md-2"></div>
          <div class="col-md-8">
            <table id="example" class="table">
              <thead>
                <th>Id</th>
                <th>Name</th>
                <th>Email</th>
                <th>Address</th>
                <th>Phone</th>
                <th>Options</th>
              </thead>
              <tbody>
              </tbody>
            </table>
          </div>
          <div class="col-md-2"></div>
        </div>
      </div>
    </div>
  </div>
  <!-- Optional JavaScript; choose one of the two! -->
  <!-- Option 1: Bootstrap Bundle with Popper -->
  <script src="js/jquery-3.6.0.min.js" crossorigin="anonymous"></script>
  <script src="js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
  <script type="text/javascript" src="js/dt-1.10.25datatables.min.js"></script>
  <!-- Option 2: Separate Popper and Bootstrap JS -->
  <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.min.js" integrity="sha384-Atwg2Pkwv9vp0ygtn1JAojH0nYbwNJLPhwyoVbhoPwBhjQPR5VtM2+xf0Uwh9KtT" crossorigin="anonymous"></script>
  -->
  <script type="text/javascript">
    $(document).ready(function() {
      $('#example').DataTable({
        "fnCreatedRow": function(nRow, aData, iDataIndex) {
          $(nRow).attr('id', aData[0]);
        },
        'serverSide': 'true',
        'processing': 'true',
        'paging': 'true',
        'order': [],
        'ajax': {
          'url': 'student-actions.php',
          'type': 'post',
          'data': {
                'action': 'select',
          }
        },
        "aoColumnDefs": [{
            "bSortable": false,
            "aTargets": [5]
          },

        ]
      });
    });

    $(document).on('submit', '#addeditStudent', function(e) {
        e.preventDefault();

        var operationType = $('#operationType').val();

        if (operationType === 'add') {
          var operationType = $('#operationType').val();
          var name = $('#addStudentField').val();
          var email = $('#addEmailField').val();
          var address = $('#addAddressField').val();
          var phone = $('#addPhoneField').val();

          if (name != '' && email != '' && address != '' && phone != '') {
            $.ajax({
              url: "student-actions.php",
              type: "post",
              data: {
                action: 'insert',
                name: name,
                email: email,
                address: address,
                phone: phone
              },
              success: function(data) {
                var json = JSON.parse(data);
                var status = json.status;
                if (status == 'true') {
                  mytable = $('#example').DataTable();
                  mytable.draw();
                  
                  alert("Record inserted succesfully");

                  $('#addStudentField').val("");
                  $('#addEmailField').val("");
                  $('#addAddressField').val("");
                  $('#addPhoneField').val("");
                  $('#id').val("");
                  $('#trid').val("");
                  $('#operationType').val("");
                  $('#addeditStudentModal').modal('hide');
                } else {
                  alert('failed');
                }
              }
            });
          } else {
            alert('Fill all the required fields');
          }

        }
        else if(operationType === 'edit')
        {      
        //var tr = $(this).closest('tr');
        var name = $('#addStudentField').val();
        var email = $('#addEmailField').val();
        var address = $('#addAddressField').val();
        var phone = $('#addPhoneField').val();
        var trid = $('#trid').val();
        var id = $('#id').val();
        if (name != '' && email != '' && address != '' && phone != '') {
          $.ajax({
            url: "student-actions.php",
            type: "post",
            data: {
              action: 'update',
              name: name,
              email: email,
              address: address,
              phone: phone,
              id: id
            },
            success: function(data) {
              var json = JSON.parse(data);
              var status = json.status;
              if (status == 'true') {
                table = $('#example').DataTable();
               
                var button = '<td><a href="#" data-id="' + id + '" class="btn btn-info btn-sm editbtn">Edit</a>  <a href="#"  data-id="' + id + '"  class="btn btn-danger btn-sm deleteBtn">Delete</a></td>';
                var row = table.row("[id='" + trid + "']");
                row.row("[id='" + trid + "']").data([id, name, email, address, phone, button]);
               
                alert("Record updated succesfully");

                $('#addStudentField').val("");
                $('#addEmailField').val("");
                $('#addAddressField').val("");
                $('#addPhoneField').val("");
                $('#id').val("");
                $('#trid').val("");
                $('#operationType').val("");
                $('#addeditStudentModal').modal('hide');
              } else {
                alert('failed');
              }
            }
          });
        } else {
          alert('Fill all the required fields');
        }
      }
    });


    $('#example').on('click', '.editbtn ', function(event) {
      var table = $('#example').DataTable();
      var trid = $(this).closest('tr').attr('id');
      // console.log(selectedRow);
      $('#operationType').val('edit');
      var rowData = table.row('#' + trid).data();

      var id = rowData[0];         
      var name = rowData[1];       
      var email = rowData[2];      
      var address = rowData[3];   
      var phone = rowData[4];   

      var id = $(this).data('id');

      $('#addeditStudentModal').modal('show');

      $('#addStudentField').val(name);
      $('#addEmailField').val(email);
      $('#addAddressField').val(address);
      $('#addPhoneField').val(phone);
      $('#id').val(id);
      $('#trid').val(trid);

    });

    $(document).on('click', '.deleteBtn', function(event) {
      var table = $('#example').DataTable();
      event.preventDefault();
      var id = $(this).data('id');
      if (confirm("Are you sure want to delete this User ? ")) {
        $.ajax({
          url: "student-actions.php",
          data: {
            action: 'delete',
            id: id
          },
          type: "post",
          success: function(data) {
            var json = JSON.parse(data);
            status = json.status;
            if (status == 'success') {
              //table.fnDeleteRow( table.$('#' + id)[0] );
              //$("#example tbody").find(id).remove();
              //table.row($(this).closest("tr")) .remove();
              $("#" + id).closest('tr').remove();
              alert("Record deleted succesfully");
            } else {
              alert('Failed');
              return;
            }
          }
        });
      } else {
        return null;
      }

    })

    $('#addButton').click(function() {
      $('#operationType').val('add');
    });

  </script>

  <!-- Add and Edit Student Modal -->
  <div class="modal fade" id="addeditStudentModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Add and Edit Student</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="addeditStudent" action="">
            <div class="mb-3 row">
              <input type="hidden" name="id" id="id" value="">
              <input type="hidden" name="trid" id="trid" value="">
              <input type="hidden" id="operationType" name="operationType" value="">
              <label for="addStudentField" class="col-md-3 form-label">Name</label>
              <div class="col-md-9">
                <input type="text" class="form-control" id="addStudentField" name="name">
              </div>
            </div>
            <div class="mb-3 row">
              <label for="addEmailField" class="col-md-3 form-label">Email</label>
              <div class="col-md-9">
                <input type="email" class="form-control" id="addEmailField" name="email">
              </div>
            </div>
            <div class="mb-3 row">
              <label for="addAddressField" class="col-md-3 form-label">Address</label>
              <div class="col-md-9">
                <input type="text" class="form-control" id="addAddressField" name="address">
              </div>
            </div>
            <div class="mb-3 row">
              <label for="addPhoneField" class="col-md-3 form-label">Phone</label>
              <div class="col-md-9">
                <input type="text" class="form-control" id="addPhoneField" name="Phone">
              </div>
            </div>
            <div class="text-center">
              <button type="submit" class="btn btn-primary">Submit</button>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
</body>

</html>
<script type="text/javascript">
  //var table = $('#example').DataTable();
</script>