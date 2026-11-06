<div class="container-fluid mt-5">
  <div class="row">
    <div class="col-md-9">
      <h1 class="title-header mb-10px">รายการค่าใช้จ่ายของพนักงาน</h1>
      <p class="sub-title text-secondary">
        รายการหักเงินจากรายได้พนักงาน <span class="text-danger">*รายการเหล่านี้จะถูกนำมาคำนวณเมื่อทำเงินเดือนของพนักงาน</span>
      </p>
    </div>
    <div class="col-md-3 mb-4">
      <a href="employee_cost_add.php?id=<?= $id ?>&mode<?= $mode ?>&page=<?= $page ?>" class="btn btn-primary border-radius-15px btn-res-150 ms-auto">ลงบันทึกค่าใช้จ่าย</a>
    </div>
  </div>

  <div class="row my-4">
    <div class="col-lg-12">
      <div class="card-box-tbl">
        <div class="table-responsive mt-10px">
          <table id="table_emp_financial" class="table">
            <thead>
              <tr>
                <th>ชื่อค่าใช้จ่าย</th>
                <th>ยอดเต็ม</th>
                <th>งวดหัก</th>
                <th>ยอดหักต่อครั้ง (บาท)</th>
                <th>ยอดหักแล้ว / ยอดเต็ม</th>
                <th>สถานะรายการ</th>
                <th>หมายเหตุ</th>
                <th></th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>