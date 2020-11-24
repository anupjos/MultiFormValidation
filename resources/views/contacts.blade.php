<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Contacts</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css2?family=Open+Sans&display=swap" rel="stylesheet">

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">

        <script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=" crossorigin="anonymous"></script>
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #000000;
                font-family: 'Open Sans', sans-serif;
                font-weight: 200;
                height: 100vh;
            }

            .full-height {
                height: 100vh;
                margin-top: 20px;
            }

            hr {
                height: 1px;
                background-color: #343a40;
            }
        </style>
    </head>
    <body>
        <div class="full-height">
            <div class="container-fluid">
                
                <div id="alert">
                        
                </div>
                <div class="row">
                    <div class="col-6">
                        <h2>
                            Multi Contact Form
                        </h2>
                    </div>
                    <div class="col-6">
                        <div class="float-right">
                            <a href="javascript:void(0);" onclick="addContact();" class="btn btn-dark">Add Contact</a>
                            <a href="javascript:void(0);" onclick="validateContact();" class="btn btn-dark">Validate</a>
                            <a href="javascript:void(0);" onclick="saveContact();" id="saveContact" class="btn btn-dark">Save</a>
                        </div>
                    </div>
                </div>
                <hr>
                <input type="hidden" id="formCount" value="{{count($contacts)}}">
                <div class="row" id="contactForms">
                    @foreach($contacts as $key => $contact)
                        <div class="col-6 mt-3" id="{{$key}}">
                            <div class="row">
                                <div class="col-6">
                                    <h3>Contact</h3>
                                </div>
                                <div class="col-3">
                                    <span class="float-right"><a href="javascript:void(0);" 
                                        onclick="removeContact( {{$key}} );" class="btn btn-dark">Remove</a></span>
                                </div>
                            </div>  
                            <form class="mt-2">
                                @csrf
                                <div class="form-group row">
                                    <div class="col-3">
                                        <label for="name">Name</label>
                                    </div>
                                    <div class="col-6">
                                        <input type="text" class="form-control" id="name" name="name" value="{{$contact['name']}}">
                                        <small id="name-error" class="errorText form-text text-danger"></small>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-3">
                                        <label for="email">Email address</label>
                                    </div>
                                    <div class="col-6">
                                        <input type="text" class="form-control" id="email" name="email" value="{{$contact['email']}}">
                                        <small id="email-error" class="errorText form-text text-danger"></small>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-3">
                                        <label for="phone">Phone Number</label>
                                    </div>
                                    <div class="col-6">
                                        <input type="text" class="form-control" id="phone" name="phone" value="{{$contact['phone']}}">
                                        <small id="phone-error" class="errorText form-text text-danger"></small>
                                    </div>
                                </div>
                            </form>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <script>
            function addContact() {
                var formCount = document.getElementById('formCount');
                formCount.value = Number(formCount.value) + 1;
                var cf = document.getElementById('contactForms');
                cf.insertAdjacentHTML('beforeend', 
                    `<div class="col-6 mt-3" id="`+ formCount.value +`">
                        <div class="row">
                            <div class="col-6">
                                <h3>Contact</h3>
                            </div>
                            <div class="col-3">
                                <span class="float-right"><a href="javascript:void(0);" onclick="removeContact(`+ formCount.value +`);" class="btn btn-dark">Remove</a></span>
                            </div>
                        </div>  
                        <form class="mt-2">
                            @csrf
                            <div class="form-group row">
                                <div class="col-3">
                                    <label for="name">Name</label>
                                </div>
                                <div class="col-6">
                                    <input type="text" class="form-control" id="name" name="name">
                                    <small id="name-error" class="errorText form-text text-danger"></small>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-3">
                                    <label for="email">Email address</label>
                                </div>
                                <div class="col-6">
                                    <input type="text" class="form-control" id="email" name="email">
                                    <small id="email-error" class="errorText form-text text-danger"></small>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-3">
                                    <label for="phone">Phone Number</label>
                                </div>
                                <div class="col-6">
                                    <input type="text" class="form-control" id="phone" name="phone">
                                    <small id="phone-error" class="errorText form-text text-danger"></small>
                                </div>
                            </div>
                        </form>
                    </div>`
                );
            }

            function removeContact(id){
                var cform = document.getElementById(id);
                cform.remove();
                var remForms = [];
                document.querySelectorAll("form").forEach(f => {
                    var obj = {};
                    f.querySelectorAll("input[type=text]").forEach(e => obj[e.name] = e.value || "");
                    remForms.push(obj);
                });
                $.ajax({ 
                    type: "POST", 
                     headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    dataType: "json", 
                    url: "/ajax/remove", 
                    data: {remForms: remForms}, 
                    success: function(data) 
                    {   
                    }
                }); 
            }

            function validateContact(){
                var inputs, index;

                inputs = document.querySelectorAll('input[type=text]');
                errors = document.querySelectorAll('small');
                var alphAndSpace = /^[a-zA-Z-,]+(\s{0,1}[a-zA-Z-, ])*$/;
                var numbers = /^[0-9]+$/;
                for (index = 0; index < inputs.length; ++index) {
                    if(inputs[index].value == ''){
                        var capitalized = inputs[index].id.charAt(0).toUpperCase()+ inputs[index].id.slice(1);
                        errors[index].innerHTML = capitalized +' is required';
                    }
                    else if(inputs[index].id == 'name'){
                        if(!inputs[index].value.match(alphAndSpace)){
                            errors[index].innerHTML = 'Invalid Name';
                        }else{
                            errors[index].innerHTML = '';
                        }
                    }
                    else if(inputs[index].id == 'email'){
                        atpos = inputs[index].value.indexOf("@");
                        dotpos = inputs[index].value.lastIndexOf(".");
                        if (atpos < 1 || ( dotpos - atpos < 2 )) {
                            errors[index].innerHTML = 'Invalid Email Address';
                        }
                        else{
                            errors[index].innerHTML = '';
                        }
                    }
                    else if(inputs[index].id == 'phone'){
                        if(!inputs[index].value.match(numbers)){
                            errors[index].innerHTML = 'Invalid Phone Number';
                        }else{
                            errors[index].innerHTML = '';
                        }
                    }
                    else{
                        errors[index].innerHTML = '';
                    }
                    
                } 
            }

            function saveContact(){
                var cForms = [];
                document.querySelectorAll("form").forEach(f => {
                    var obj = {};
                    f.querySelectorAll("input[type=text]").forEach(e => obj[e.name] = e.value || "");
                    cForms.push(obj);
                });
                $.ajax({ 
                    type: "POST", 
                     headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    dataType: "json", 
                    url: "/ajax/save", 
                    data: {cForms: cForms}, 
                    success: function(data) 
                    {   if(!data)   
                            $('#alert').html("<div class='alert alert-success'>Validation Success & Contacts Saved!</div>");
                        else
                            $('#alert').html("<div class='alert alert-danger'>Validation Failed!</div>");
                    }
                }); 
            }
        </script>
    </body>
</html>
