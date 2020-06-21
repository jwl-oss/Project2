

let file = document.getElementById('file');

let image = document.getElementById("PicFromUser");

file.onchange = function() {

    let fileData = this.files[0];//获取到一个FileList对象中的第一个文件( File 对象),是我们上传的文件

    alert(fileData);

    let pettern = /^image/;

    console.info(fileData.type)

    if (!pettern.test(fileData.type)) {

        alert("图片格式不正确");

        return;

    }

    let reader = new FileReader();

    reader.readAsDataURL(fileData);//异步读取文件内容，结果用data:url的字符串形式表示

    /*当读取操作成功完成时调用*/

    reader.onload = function(e) {

        console.log(e); //查看对象

        console.log(this.result);//要的数据 这里的this指向FileReader（）对象的实例reader

        image.setAttribute("src", this.result);

    }

    document.getElementById("placeholder").style.display = "none";

}


let cities = {};

let CouRegSelection = document.getElementById('Countries');

CouRegSelection.addEventListener("load",setCity(CouRegSelection,CouRegSelection.form.Cities));

CouRegSelection.addEventListener("change",setCity(CouRegSelection,CouRegSelection.form.Cities));



function setCity(country,city) {

    city.options[0] = new Option();

    city.options[0].text = '-Cities-';

    city.options[0].value = 'default';

    city.options[0].setAttribute("selected","selected");



    let mode = country.classList[0];

    mode = mode.substring(1);

    let couRegCity;

    let CouRegISO = country.value;





    city.length=1;



    let xmlhttp = new XMLHttpRequest();

    xmlhttp.onreadystatechange=function()

    {

        if (xmlhttp.readyState===4 && xmlhttp.status===200)

        {

            couRegCity = xmlhttp.response;

            let CouRegJSON = JSON.parse(couRegCity);

            for(let i = 0;i < CouRegJSON.length;i++){

                cities[i] = {};

                cities[i]['CityCode'] = CouRegJSON[i].GeoNameID;

                cities[i]['Name'] = CouRegJSON[i].AsciiName;

            }



            for(let i=0; i< CouRegJSON.length; i++) {

                let j = i+1;

                city.options[j] = new Option();

                city.options[j].text = cities[i]['Name'];

                city.options[j].value = cities[i]['CityCode'];

                if(mode !== null && city.options[j].value === mode){

                    city.options[j].setAttribute("selected","selected");

                }

            }



        }

    }

    xmlhttp.open("GET",'../src/GetCityUP.php?ISO='+CouRegISO,true);

    xmlhttp.send();

}