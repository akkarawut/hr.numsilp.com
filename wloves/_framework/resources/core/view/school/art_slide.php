<div class="content-body-container">
  <div class="row">
    <div class="col-12">
      <div class="ex-slide-box">
        <?php
        $options = [
          'items_page' => 3,
          'navigation' => [
            'is_show' => true,
          ],
          'pagination' => [
            'is_show' => true,
          ],
          'space_gap' => 10
        ];
        Artslide::normalSlide('ex_normal', $options);
        ?>
      </div>

      <div class="ex-slide-box mt-30px">
        <?php
        $options = [
          'items_page' => 5,
          'navigation' => [
            'is_show' => false,
          ],
          'pagination' => [
            'is_show' => false,
          ],
          'space_gap' => 10
        ];
        Artslide::normalSlide('ex_normal_2', $options);
        ?>
      </div>

    </div>
  </div>
</div>