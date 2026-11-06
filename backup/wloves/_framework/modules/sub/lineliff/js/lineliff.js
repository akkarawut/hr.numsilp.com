var lineliff = {
  getLineInfomation: async function (liffId, callback) {
    var result = await liff
      .init({
        liffId: liffId,
      })
      .then(() => {
        var result = {};
        if (liff.isInClient()) {
          result = liff.getProfile().then((res) => {
            return res;
          });
        } else {
          console.log("Please enter link via Line Application!");
        }
        return result;
      });
    try {
      callback(result);
    } catch (error) {
      console.log(error);
    }
  },
};

w.extend({ lineliff });
