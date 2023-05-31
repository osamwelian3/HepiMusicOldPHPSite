$(document).ready(function() {
    if (isFrontend) {
        $('body').on('click', '.thumbs_btn', function() {
            var $this = $(this);
            var song_id = $(this).attr('data-id');
            var vote = $(this).attr('data-vote');

            $.ajax({
                url: voteUrl,
                type: 'POST',
                data: { 'song_id': song_id, 'vote': vote },
                dataType: 'json',
                success: function(result) {
                    if (result.status) {
                        showMessage("success", result.message);

                        if (result.data.vote == 1) {
                            $('#vote_'+song_id).find('.up_vote').css({ 'background': '#F3B106', 'color': '#fff' });
                            $('#vote_'+song_id).find('.down_vote').css({ 'background': '#fff', 'color': 'black' });
                            $('#vote_'+song_id).find('.up_count').text(result.data.count_up_votes);
                            $('#vote_'+song_id).find('.down_count').text(result.data.count_down_votes);
                            $('#vote_'+song_id).find('.up_vote').removeClass('thumbs_btn');
                            $('#vote_'+song_id).find('.down_vote').addClass('thumbs_btn');
                        } else {
                            $('#vote_'+song_id).find('.up_vote').css({ 'background': '#fff', 'color': 'black' });
                            $('#vote_'+song_id).find('.down_vote').css({ 'background': '#F3B106', 'color': '#fff' });
                            $('#vote_'+song_id).find('.up_count').text(result.data.count_up_votes);
                            $('#vote_'+song_id).find('.down_count').text(result.data.count_down_votes);
                            $('#vote_'+song_id).find('.up_vote').addClass('thumbs_btn');
                            $('#vote_'+song_id).find('.down_vote').removeClass('thumbs_btn');
                        }
                    } else {
                        showMessage("error", result.message);
                    }
                }
            });
        });

        $('body').on('click', '.remove_like', function() {
            var $this = $(this);
            var song_id = $(this).attr('data-id');
            var vote = $(this).attr('data-vote');

            $.ajax({
                url: removeLike,
                type: 'POST',
                data: { 'song_id': song_id, 'vote': vote },
                dataType: 'json',
                success: function(result) {
                    if (result.status) {
                        $($this).closest('.song_row').remove();

                        if (result.data.vote_count == 0) {
                            $('.right-panel-main').html('');
                            $('.right-panel-main').append('<h3 style="text-align: center; color: #fff;">No result found.</h3>');
                        }

                        showMessage("success", result.message);
                    } else {
                        showMessage("error", result.message);
                    }
                }
            });
        });

        $('body').on('click', '.like_btn', function() {
            var $this = $(this);
            var id = $(this).attr('data-id');

            $.ajax({
                url: likeUrl,
                type: 'POST',
                data: { 'id': id },
                dataType: 'json',
                success: function(result) {
                    if (result.status) {
                        showMessage("success", result.message);

                        $('#vote_'+id).find('.like_btn').css({ 'background': 'red', 'color': '#fff' });
                        $($this).attr('title', 'Liked');
                        $($this).removeClass('like_btn');
                        $($this).addClass('liked');
                    } else {
                        showMessage("error", result.message);
                    }
                }
            });
        });

        document.addEventListener('play', function(e) {
            var audios = document.getElementsByTagName('audio');

            for (var i = 0, len = audios.length; i < len; i++) {
                if (audios[i] != e.target) {
                    audios[i].pause();
                }
            }
        }, true);

        var audList = document.getElementsByTagName("audio");

        for (var i = 0; i < audList.length; i++) {
            audList[i].addEventListener("play", onPlay, false);
        }

        function onPlay(e) {
            var id = e.target.id;
            
            $.ajax({
                url: streamUrl,
                method: "POST",
                data: { 'id': id },
                success: function (response) {

                }
            });
        };

        $('body').on('change', '.category_filter', function() {
            var category = $(this).val();

            $.ajax({
                url: filterUrl,
                method: "POST",
                data: { 'category': category },
                success: function (response) {
                    $('.right-panel-main').html('');
                    $('.right-panel-main').html(response);

                    var audList = document.getElementsByTagName("audio");

                    for (var i = 0; i < audList.length; i++) {
                        audList[i].addEventListener("play", onPlay, false);
                    }
                }
            });
        });

        function ajaxCall() {
            // var $boxes = $('input[name=categories_checkbox]:checked');
            // var length = $boxes.length;
            // categoryIdArray = [];
            // categoryNameArray = [];
            // $boxes.each(function(){
            //     categoryIdArray.push($(this).attr('data-id'));
            //     categoryNameArray.push($(this).attr('data-value'));
            // });

            var categoryIdArray = $('input[name=categories_radio]:checked').val();
            
            if (!categoryIdArray) {
                categoryIdArray = 0;
            }

            $.ajax({
                url: filterUrl,
                method: "POST",
                data: { categoryIdArray:categoryIdArray },
                success: function (response) {
                    $('.right-panel-main').html('');
                    $('.right-panel-main').html(response);

                    var audList = document.getElementsByTagName("audio");

                    for (var i = 0; i < audList.length; i++) {
                        audList[i].addEventListener("play", onPlay, false);
                    }
                }
            });
        }

        $(document).find("input:checked[type='radio']").addClass('bounce');
        $('body').on('click', 'input:radio[name="categories_radio"]', function(e) {
            $(this).prop('checked', false);
            $(this).toggleClass('bounce');
            $("."+$(this).attr("value")).remove();
            if( $(this).hasClass('bounce') ) {
                $(this).prop('checked', true);
                $(document).find("input:not(:checked)[type='radio']").removeClass('bounce');
            }

            ajaxCall();
        });

        // $('body').on('change', '.categories_checkbox', function(e) {
        //     if($(this).is(':checked'))
        //     {
                
        //     }
        //     else
        //     {
        //         $("."+$(this).attr("data-value")).remove();
        //     }

        //     var $boxes = $('input[name=categories_checkbox]:checked');
        //     var length = $boxes.length;
        //     categoryIdArray = [];
        //     categoryNameArray = [];
        //     $boxes.each(function(){
        //         categoryIdArray.push($(this).attr('data-id'));
        //         categoryNameArray.push($(this).attr('data-value'));
        //     });

        //     ajaxCall();
        // });

        $('body').on('click','.share_song_btn', function(event) {
            var id = $(this).attr('data-id');

            $('.share_box').not('.share_box_'+id).removeClass('share_display');
            $('.share_box_'+id).toggleClass('share_display');
        });

        $('body').on('click', '.social_btn', function() {
            $(this).closest('.share_box').removeClass('share_display');
        });
    } else {
    	var listTable = $('#listTable').DataTable({
            order: [],
            searching: true,
            pageLength: 10,
            processing: true,
            serverSide: true,
            ajax: {
                url: apiUrl,
                type: 'GET',
                headers: {
                    'X-XSRF-TOKEN': $('meta[name=csrf-token]').attr('content'),
                },
            },
            columns: [
                { data: 'get_category.category_name' },
                { data: 'song_title' },
                { data: 'created_at', name: 'created_at' },
                { data: 'stream_count', name: 'stream_count' },
                {
                    data: 'total_votes',
                    name: 'total_votes',
                },
                // {
                //     data: null,
                //     name: 'status',
                //     render: function (data, type, full, meta) {
                //         let html = '<input class="status_switch" type="checkbox" ' + ((data.status) ? 'checked' : '') + ' data-on-color="success" data-id="' + data.id + '" data-size="small">';
                //         return html;
                //     },
                //     orderable: false,
                //     searchable: false
                // },
                {
                    sortable: false,
                    render: function(_,_, full) {
                        var contactId = full['id'];

                        if (contactId) {
                            var actions = '';
                            actions += ' <a href="javascript:void(0)" data-id="'+ contactId +'" class="btn-sm btn-primary edit-row">Edit</a>';
                            actions += ' <a href="javascript:void(0)" data-id="'+ contactId +'" class="btn-sm btn-danger delete-row">Delete</a>';
                            
                            return actions;
                        }
                        return '';
                    },
                },
            ],
            "drawCallback": function( settings ) {
                $(".status_switch").bootstrapSwitch({
                    // onSwitchChange: function(e, state) {
                    //     changeStatus(state, e.currentTarget.dataset.id);
                    // }
                });
            }
        });

        $(document).on('switchChange.bootstrapSwitch', '.status_switch', function (e) {
            e.preventDefault();
            if ($(this).prop("checked") == true) {
                var status = 1;
            } else {
                var status = 0;
            }

            $.ajax({
                url: changeStatusUrl,
                method: 'POST',
                data: {
                    id: $(this).data('id'),
                    status: status,
                },
                dataType: 'json',
                success: function (result) {
                    if (result.status) {
                        showMessage("success", result.message);
                    } else {
                        $('#listTable').DataTable().ajax.reload();
                        showMessage("error", result.message);
                    }
                },
                error: function (error) {
                    
                }
            })
        });

        $('.add-new').click(function(event) {
            $('#edit-id').val("");
            $('.modal-lable-class').html('Add');
            $('.invalid-feedback strong').html("");

            $('#add-form')[0].reset();
            $('#add-form').find('.display_img').attr("src", defaultImage);
            $('#add-form').find('.display_mp3').html('<audio controls><source src="" type="audio/mpeg"></source></audio>');
            $('#add-modal').modal('show');
        });

        $('#add-form').submit(function(event) {
            event.preventDefault();
            var $this = $(this);
            var dataString = new FormData($('#add-form')[0]);
            $('.invalid-feedback strong').html('');

            $.ajax({
                url: addUrl,
                type: 'POST',
                data: dataString,
                processData: false,
                contentType: false,
                beforeSend: function() {
                    $($this).find('button[type="submit"]').prop('disabled', true);
                },
                success: function(result) {
                    $($this).find('button[type="submit"]').prop('disabled', false);
                    if (result.status) {
                        $this[0].reset();
                        $('#edit-id').val(0);
                        showMessage("success", result.message);

                        $('#listTable').DataTable().ajax.reload();
                        
                        setTimeout(function() {
                            $('#add-modal').modal('hide');
                        }, 1000);
                    } else if (!result.status && result.message){
                        showMessage("error", result.message);
                    } else {
                        first_input = "";
                        $('.error').html("");
                        $.each(result.error, function(key) {
                            if(first_input=="") first_input=key;
                            $('#add-form .error-'+key).find('strong').html(result.error[key]);                        
                        });
                        $('#add-form').find("."+first_input).focus();
                    }
                },
                error: function(error) {
                    $($this).find('button[type="submit"]').prop('disabled', false);
                    alert('Something went wrong!', 'error');
                    // location.reload();
                }
            });
        });

        $('body').on('click','.edit-row',function(event) {
            var id = $(this).attr('data-id');
            $('.invalid-feedback strong').html('');
            $('#add-form')[0].reset();

            $.ajax({
                url: detailUrl+'?id='+id,
                type: 'GET',
                dataType: 'json',
                success: function(result) {
                    if (result.status) {
                        $('#edit-id').val(id);
                        
                        $('.modal-lable-class').html('Edit');
                        $('#add-modal').modal('show');
                        
                        $('#add-form').find('#category_id').val(result.data.category_id);
                        $('#add-form').find('#song_title').val(result.data.song_title);

                        if (result.data.thumbnail) {
                            $('#add-form').find('.display_img').attr("src", result.data.thumbnail);
                        }

                        if (result.data.mp3_file) {
                            $('#add-form').find('.display_mp3').html('<audio controls><source src="'+result.data.mp3_file+'" type="audio/mpeg"></source></audio>');
                        }
                    }
                }
            });    
        });

        $('body').on('click','.up_vote_btn', function(event) {
            var id = $(this).attr('data-id');
            var votes = $(this).attr('data-votes');
            var song = $(this).attr('data-song');

            $('.modal-lable-class').html('Edit Up Votes - '+song);
            $('.vote_label').html('Up Votes');
            $('.song_votes').val(votes);
            $('.vote_type').val('up');
            $('.song_id').val(id);
            $('#vote-modal').modal('show');
        });

        $('body').on('click','.down_vote_btn', function(event) {
            var id = $(this).attr('data-id');
            var votes = $(this).attr('data-votes');
            var song = $(this).attr('data-song');

            $('.modal-lable-class').html('Edit Down Votes - '+song);
            $('.vote_label').html('Down Votes');
            $('.song_votes').val(votes);
            $('.vote_type').val('down');
            $('.song_id').val(id);
            $('#vote-modal').modal('show');
        });

        $('#vote-form').submit(function(event) {
            event.preventDefault();
            var $this = $(this);
            var song_id = $('.song_id').val();
            var vote_type = $('.vote_type').val();
            var song_votes = $('.song_votes').val();
            // var dataString = new FormData($('#vote-form')[0]);
            $('.invalid-feedback strong').html('');

            if (song_votes < 0) {
                $('#vote-form .error-song_votes').find('strong').html('Votes must be greater than or equal to 0.');
            } else {
                if (song_votes.length > 0) {
                    $.ajax({
                        url: editVote,
                        type: 'POST',
                        data: { 'song_id': song_id, 'vote_type': vote_type, 'song_votes': song_votes },
                        dataType: 'json',
                        beforeSend: function() {
                            $($this).find('button[type="submit"]').prop('disabled', true);
                        },
                        success: function(result) {
                            $($this).find('button[type="submit"]').prop('disabled', false);
                            if (result.status) {
                                showMessage("success", result.message);
                            } else {
                                showMessage("error", result.message);
                            }
                            $('#listTable').DataTable().ajax.reload();

                            setTimeout(function() {
                                $('#vote-modal').modal('hide');
                            }, 1000);
                        },
                    });
                } else {
                    $('#vote-form .error-song_votes').find('strong').html('Votes field cannot be blank.');
                }
            }
        });

        $('body').on('click','.delete-row',function(event) {
            var id = $(this).attr('data-id');

            if (confirm('Are you sure you want to delete ?')) {
                $.ajax({
                    url: deleteUrl+'?id='+id,
                    type: 'POST',
                    dataType: 'json',
                    success: function(result) {
                        if (result.status) {
                            showMessage("success", result.message);
                        } else {
                            showMessage("error", result.message);
                        }
                        $('#listTable').DataTable().ajax.reload();
                    }
                });    
            }
        });
    }
});