<div class="col-md-9">
  <div class="user-topic">
    <div>
      <h6 class="topic">Customer Categories</h6>
      <p class="sub-topic">Manage your customer categories. use to grouping customer.</p>
    </div>
    <button type="button" class="form-btn" <?=Tiwdal::register('add_category_modal');?>>
      <span class="">ADD NEW CATEGORY</span>
    </button>
  </div>

  <div class="w-loves-card p-5px">
    <div id="setting_category" class="container-pagination" <?=Homepagify::createHomepagify('setting_category', '?c='.$_GET['c'].'&type='.$type)?>>
      <div class="table-responsive">
        <table class="table table-sort table-search">
          <thead>
            <tr>
              <th nowrap >Customer Category name</th>
              <th nowrap >Description</th>
              <th nowrap class="text-right">Customer used this tag</th>
              <th nowrap class="thin-cell"></th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
  </div>
</div>