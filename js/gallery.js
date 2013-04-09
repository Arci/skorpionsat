function loadPhotos(id){
                //evidezio l'album selezionato
                $("#albums").children().removeClass("underlined");
                $('#'+id).addClass("underlined");
                //ricarico il div
                $('#AlbumView').fadeOut(1);
                //carico informazioni
                $.ajax({
                    type: "GET",
                    url: 'load.php',
                    contentType: "application/json",
                    data: "id="+id,
                    success: function(data) {
                        var response = JSON.parse(data);
                        console.log(response);
                        if (response.success){
                            $('#title').html(response.title);
                            $('#date').html(response.date);
                            $('#description').html(response.description);
                            var imagesBuffer = "<div class='row'>";
                            var rowCounter = 0;
                            for(var i=0; i < response.photos.length; i++){
                                if(rowCounter==2){
                                    imagesBuffer += "<div class='clear'></div></div>";
                                    imagesBuffer += "<div class='row'>";
                                    rowCounter = 0;
                                }
                                imagesBuffer += "<div class='left'><img class='big' src='site/" + response.photos[i] + "' /></div>";
                                rowCounter ++;
                            }
                            if(rowCounter > 0){
                               imagesBuffer += "<div class='clear'></div></div>";
                            }
                            $('#photos').html(imagesBuffer);
                        }else{
                            $('#photos').html("<p class=\"error\">" + response.message + "<p>");
                        }
                        $('#AlbumView').fadeIn("slow");
                    }
                });
            };