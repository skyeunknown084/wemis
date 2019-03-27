<div class="container" id="salary">
	<ol class="breadcrumb">
    <li class="breadcrumb-item">
    <a href="#salary">Payroll</a>
    </li>
    <!-- <li class="breadcrumb-item active nav-item tabClicker" data-tab="tabs-1">Profile</li> -->
    <!-- <li class="breadcrumb-item active nav-item tabClickers" data-tab="tabs-1">Member Info</li>
    <li class="breadcrumb-item active nav-item tabClickers" data-tab="tabs-2">Attendance</li>
    <li class="breadcrumb-item active nav-item tabClickers" data-tab="tabs-3">Schedule</li>
    <li class="breadcrumb-item active nav-item tabClickers" data-tab="tabs-4">Salary</li> -->
    </ol>

    <p></p>
    <button class="btn btn-primary" data-toggle="modal" data-target="#addData">Insert Data</button>
    <!-- Logout Modal -->
    <div class="modal fade" id="addData" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Add Data</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form>
                <div class="form-group">
                    <label for="nm">Full Name</label>
                    <input type="text" class="form-control" id="nm" aria-describedby="emailHelp" placeholder="Full Name">
                </div>
                <div class="form-group">
                    <label for="em">Email</label>
                    <input type="email" class="form-control" id="em" aria-describedby="emailHelp" placeholder="Email Address">
                </div>
                <div class="form-group">
                    <label for="hp">Phone Number</label>
                    <input type="number" class="form-control" id="hp" aria-describedby="emailHelp" placeholder="Phone Number">
                </div>
                <div class="form-group">
                    <label for="al">Address</label>
                    <input type="text" class="form-control" id="al" aria-describedby="emailHelp" placeholder="Address">
                </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            <button type="submit" onclick="saveData()" class="btn btn-primary" data-dismiss="modal">Save</button>
          </div>
        </div>
      </div>
    </div>
    <p></p>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th width="40">ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Address</th>
                <th width="160">Action</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>

    <div class="modal fade" id="edit" tabindex="-1" role="dialog" aria-labelledby='editLabel-<?php echo $row["id"] ?>' aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id='editLabel-<?php echo $row["id"] ?>'>Edit Data</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form>
                <div class="form-group">
                    <input type="hidden" id='nm-<?php echo $row["id"] ?>' value='<?php echo $row["id"] ?>'>
                    <label for="nm">Full Name</label>
                    <input type="text" class="form-control" id='nm-<?php echo $row["id"] ?>' aria-describedby="emailHelp" placeholder="Full Name" value='<?php echo $row["name"] ?>'>
                </div>
                <div class="form-group">
                    <label for="em">Email</label>
                    <input type="email" class="form-control" id='em-<?php echo $row["id"] ?>' aria-describedby="emailHelp" placeholder="Email Address" value='<?php echo $row["email"] ?>'>
                </div>
                <div class="form-group">
                    <label for="hp">Phone Number</label>
                    <input type="number" class="form-control" id='hp-<?php echo $row["id"] ?>' aria-describedby="emailHelp" placeholder="Phone Number" value='<?php echo $row["phone"] ?>'>
                </div>
                <div class="form-group">
                    <label for="al">Address</label>
                    <input type="text" class="form-control" id='al-<?php echo $row["id"] ?>' aria-describedby="emailHelp" placeholder="Address" value='<?php echo $row["address"] ?>'>
                </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            <button type="submit" onclick='updateData(<?php echo $row["id"] ?>)' class="btn btn-primary" data-dismiss="modal">Save</button>
          </div>
        </div>
      </div>
    </div>
</div>