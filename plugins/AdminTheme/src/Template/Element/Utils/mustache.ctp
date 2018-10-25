<script id="people" type="text/template">
    <div class="row js-people-{{index}}">
        <div class="clearfix mt15">
            <div class="col-md-5">
                <div class="input text required">
                    <label for="people-{{index}}-name">Name</label>
                    <input type="text" name="key_people[{{index}}][name]" class="form-control" required="required" maxlength="255" id="people-{{index}}-name">
                </div>
            </div>
            <div class="col-md-5">
                <div class="input text">
                    <label for="people-{{index}}-title">Title</label>
                    <input type="text" name="key_people[{{index}}][title]" class="form-control" id="people-{{index}}-title">
                </div>
            </div>
            <div class="col-md-2">
                <label>&nbsp;</label>
                <button class="btn btn-danger js-remove-element mt-24 pull-right" data-index="{{index}}" type="button">
                    <i class="fa fa-minus"></i>
                </button>
            </div>
            <div class="col-md-5">
                <div class="input text">
                    <label for="people-{{index}}-age">Age</label>
                    <input type="text" name="key_people[{{index}}][age]" class="form-control" id="people-{{index}}-age">
                </div>
            </div>
            <div class="col-md-5">
                <div class="input text">
                    <label for="people-{{index}}-photo">Photo</label>
                    <input type="file" name="key_people[{{index}}][photo]" class="form-control" required="required" id="key-people-{{index}}-photo">
                </div>
            </div>
        </div>
    </div>
</script>