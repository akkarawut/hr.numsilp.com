
w.extend({ /** extend function */
  testFunction : { /** ชื่อตัวแปร */
    test: function() { /** ฟังค์ชั่น */
      console.log('test');
    }
  }
});

// or

var testFunction = { /** ชื่อตัวแปร */
  test: function() { /** ฟังค์ชั่น */
    console.log('test');
  }
}
w.extend({testFunction}); /** extend function */


// การเรียกใช้งาน
// w.testFunction.test();