document.getElementById('sendForm').onclick = 
function () {

    let name = document.getElementById('name');
    let surname = document.getElementById('surname');
    let patronymic = document.getElementById('patronymic');
    let iban = document.getElementById('iban');

    let details = {
        'name': name.value,
        'surname': surname.value,
        'patronymic': patronymic.value,
        'iban' : iban.value
    };
    
    let formBody = [];
    for (let property in details) {
      let encodedKey = encodeURIComponent(property);
      let encodedValue = encodeURIComponent(details[property]);
      formBody.push(encodedKey + "=" + encodedValue);
    }
    formBody = formBody.join("&");

    
    fetch('https://52.149.66.146/form/myInfo', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8'
      },
      body: formBody
    })
    .then((response) => {
      console.log(response.status);
      return response.json();
    })
    .then((data) => {
      console.log(data);
    });


    return false;
}