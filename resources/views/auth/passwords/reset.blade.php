<div class="modal fade" id="forgetPasswordModel" tabindex="-1" role="dialog" aria-labelledby="forgetPasswordModelLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Password reset request form</h4>
            </div>
            <div class="modal-body">

                <form class="form-horizontal" role="form" method="POST" action="{{ route('password-reset.store') }}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label class="control-label col-sm-3" for="fullname">Full Name *:</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="fullname" placeholder="Enter your full name" name="fullname" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-sm-3" for="email">Organization Name *:</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="organization_name" placeholder="Enter Organization name with address" name="organization_name" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-sm-3" for="designation">Designation *:</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="designation" placeholder="Enter your designation" name="designation" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-sm-3" for="email">Email *:</label>
                        <div class="col-sm-9">
                            <input type="email" class="form-control" id="email" placeholder="Enter email" name="email" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-sm-3" for="phone">Phone *:</label>
                        <div class="col-sm-9">
                            <input type="tel" class="form-control" id="phone" placeholder="Enter phone" name="phone" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-sm-3" for="username">Username *:</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="username" placeholder="Enter username" name="username" required>
                        </div>
                    </div>

                    <hr>
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-9">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary pull-right">Submit Request</button>
                        </div>
                    </div>
                </form>
                </div>

            <div class="modal-footer">
                Note : * Required Fields
            </div>
        </div>
    </div>
</div>
