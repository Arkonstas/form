document.addEventListener('DOMContentLoaded', function(){

    document.querySelector('.form form').addEventListener("submit",function(e){
        e.preventDefault();

        const privacy = document.getElementById('privacy').checked;

        if(privacy)
        {
            document.querySelector('.privacy label').classList.remove('error');
            const request = new XMLHttpRequest();

            const data = new FormData(e.target);

            data.append("ajax", "Y");

            const url = document.location.href;
            
            request.open("POST", url, true);
            
            request.addEventListener("readystatechange", () => {

                if(request.readyState === 4 && request.status === 200) {     
                    console.log(request.responseText);  
                    var result = document.createElement('div');
                    result.innerHTML= request.responseText;

                    if(!!result.querySelector('.error_form'))
                    {
                        document.querySelector('.form form').innerHTML = result.querySelector('.error_form').innerHTML;
                    }
                    else 
                    {
                        document.querySelector('.form form').innerHTML = '<div class="form_top"><div class="title">Спасибо! Ваша заявка принята.</div></div>';
                    }

                }
            });

            request.send(data);
        }
        else
        {
            document.querySelector('.privacy label').classList.add('error');
        }
        console.log(privacy);

     },false);

});