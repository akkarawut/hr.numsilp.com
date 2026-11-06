<div class="col-md-9">
  <div class="user-topic">
    <div>
      <h6 class="topic">Customer Tag</h6>
      <p class="sub-topic">Manage your customer tag. use to grouping customer.</p>
    </div>
    <button type="button" class="form-btn" <?=Tiwdal::register('add_tag_modal');?>>
      <span class="">ADD NEW TAG</span>
    </button>
  </div>

  <div class="w-loves-card p-5px">
    <div id="setting_tag" class="container-pagination" <?=Homepagify::createHomepagify('setting_tag', '?c='.$_GET['c'].'&position_id='.$type)?>>
      <div class="table-responsive">
        <table class="table table-sort table-search">
          <thead>
            <tr>
              <th nowrap >Customer Category name</th>
              <th nowrap >Description</th>
              <th nowrap >Customer used this tag</th>
              <th nowrap class="thin-cell"></th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
  </div>
</div>