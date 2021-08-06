const { default: axios } = require('axios');
// const FileDownload = require('js-file-download');

require('./bootstrap');

Echo.private('notifications_for_admin')
.listen('NewImportFileStatus', (data) => {
  console.log(data);

  let progressBar = document.getElementById('progress-import');
  progressBar.style.width = `${data.progress_percentage}%`;
  progressBar.innerText = `${data.progress_percentage}%`;
})
.listen('ResultCategoriesImport', (data) => {
  console.log(data);
  let status = data.status;
  if ( status === 'finished' ) {
    document.getElementById('message-status').innerText = "Xử lý tệp thành công!";
    document.getElementById('link-admin-page').style.display = "block";

    let progressBar = document.getElementById('progress-import');
    progressBar.classList.remove('bg-success');
    progressBar.classList.add('bg-info');

    let alertCard = document.getElementById('alert-card');
    alertCard.classList.remove('alert-info');
    alertCard.classList.add('alert-success');

    let modalResult = document.getElementById('result-import');
    let totalRowsFailed = data.description.arrayRowsFail.length;
    let listRowsFailed = data.description.arrayRowsFail.map( (item) => {
      return item[0];
    } ).join(', ');
    modalResult.innerHTML = '<h6>Số hàng nhập thành công: ' + 
      `${data.description.totalRowsReaded - totalRowsFailed}/${data.description.totalRowsReaded}` + 
      '</h6>' + '<hr>' + '<h6>Danh sách các hàng nhập thất bại:</h6>' + 
      `<p>${listRowsFailed}<p>` + '<hr>' + 
      '<h6>Tải xuống file log: </h6>';
    
    let inputFilePath = document.getElementById('filePath');
    inputFilePath.value = data.description.pathLogFile;

  } else if ( status === 'failed' ) {
    document.getElementById('message-status').innerText = data.message;
    document.getElementById('message-status').style.fontWeight = "bold";
    var alertCard = document.getElementById('alert-card');
    alertCard.classList.remove('alert-info');
    alertCard.classList.add('alert-danger');
  }
})
.listen('ResultProductsImport', (data) => {
  console.log(data);
  let status = data.status;
  if (status === 'finished') {
    document.getElementById('message-status').innerText = "Xử lý tệp thành công!";
    document.getElementById('link-admin-page').style.display = "block";

    let progressBar = document.getElementById('progress-import');
    progressBar.classList.remove('bg-success');
    progressBar.classList.add('bg-info');

    let alertCard = document.getElementById('alert-card');
    alertCard.classList.remove('alert-info');
    alertCard.classList.add('alert-success');

    let modalResult = document.getElementById('result-import');

    let totalRowsProductFailed = data.description.productsImport.arrayRowsFail.length;
    let listRowsProductFailed = 'None';
    if (totalRowsProductFailed > 0) {
      rowsFailed = data.description.productsImport.arrayRowsFail.filter((item) => {
        return item[3];
      });
      listRowsProductFailed = rowsFailed.map((item) => {
        return item[0];
      }).join(', ');
    }
    let totalRowsProductSuccess = data.description.productsImport.totalRowsReaded - totalRowsProductFailed;

    let totalRowsInfoProductFailed = data.description.infoProductsImport.arrayRowsFail.length;
    let listRowsInfoProductFailed = 'None';
    if (totalRowsInfoProductFailed > 0) {
      listRowsInfoProductFailed = data.description.infoProductsImport.arrayRowsFail.map((item) => {
        return item[0];
      }).join(', ');
    }
    let totalRowsInfoProductSuccess = data.description.infoProductsImport.totalRowsReaded - totalRowsInfoProductFailed;

    let resultProduct = '<h4>Xử lý file products_data:</h4>' + 
      `<h6>Số hàng nhập thành công: ${totalRowsProductSuccess}/${data.description.productsImport.totalRowsReaded}</h6>` +
      '<h6>Danh sách các hàng nhập thất bại:</h6>' + 
      `<p>${listRowsProductFailed}</p>`;
    let resultInfoProduct = '<h4>Xử lý file infomation_products_data:</h4>' + 
      `<h6>Số hàng nhập thành công: ${totalRowsInfoProductSuccess}/${data.description.infoProductsImport.totalRowsReaded}</h6>` +
      '<h6>Danh sách các hàng nhập thất bại:</h6>' + 
      `<p>${listRowsInfoProductFailed}</p>`;

    modalResult.innerHTML = resultProduct + '<hr>' + resultInfoProduct;

    let inputFilePath = document.getElementById('logFilePath');
    inputFilePath.value = data.description.pathLogFile;

  } else if (status === 'failed') {
    document.getElementById('message-status').innerText = data.message;
    document.getElementById('message-status').style.fontWeight = "bold";
    var alertCard = document.getElementById('alert-card');
    alertCard.classList.remove('alert-info');
    alertCard.classList.add('alert-danger');
  }
});

