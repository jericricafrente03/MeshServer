<div class="modal" id="show_view_announcement_modal" tabindex="1">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header bg-gray">
                <h5 class="modal-title mx-3 text-dark" id="show_announcement_subject">
                    Subject
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body px-5 py-3" id="show_announcement_body">
                Content
            </div>
            <div class="modal-footer mx-3"  id="show_announcement_footer">
                Footer
            </div>

        </div>
    </div>
</div>


<script>
    function showViewAnnouncementByIdAndTypeModal(type, id){
        console.log("fire here type is", type, id)
        $.ajax({
            url: `/admin/view-announcement`,
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                id: id,
                type: type
            },
            dataType: 'json',
            success:function(response){
                var data = response.data;
                
                let subject = data.subject;
                let content = data.content;
                let created_at = data.formatted_pst_created_at;
                let created_by = data.user.employee.firstname +' ' + data.user.employee.lastname;

                var footer = `
                    <p>Created by: <b>${created_by}</b></p>
                    <p class="ms-auto">${created_at}</p>
                `;

                $('#show_view_announcement_modal #show_announcement_subject').html(subject);
                $('#show_view_announcement_modal #show_announcement_body').html(content);
                $('#show_view_announcement_modal #show_announcement_footer').html(footer);

                // tagAsReadBulletinAnnouncement(id)

                $('#show_view_announcement_modal').modal('show');

            }
        });
    }

    function tagAsReadBulletinAnnouncement(id)
    {
        $.ajax({
            url: `/store/bulletin/announcements/view/${id}`,
            type: 'get',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {},
            dataType: 'json',
            success:function(response){
                console.log("response");
            }
        });
    }
</script>