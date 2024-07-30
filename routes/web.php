<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Backend\AdminBookingController;
use App\Http\Controllers\Backend\BlogController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Backend\TeamController;
use App\Http\Controllers\Backend\BookareaController;
use App\Http\Controllers\Backend\BookingTourController;
use App\Http\Controllers\Backend\RoleController;
use App\Http\Controllers\Backend\RoomTypeController;
use App\Http\Controllers\Backend\RoomController;
use App\Http\Controllers\Backend\RoomListController;
use App\Http\Controllers\Backend\SettingController;
use App\Http\Controllers\Backend\TestimonicalController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\Frontend\FrontendRoomController;
use App\Http\Controllers\Frontend\BookingController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SiteController;
use App\Models\BookArea;
use App\Models\SiteSetting;

Route::middleware('auth')->group(function () {
    //USERFRONTEND
    Route::get('/', [UserController::class, 'index'])->name('frontend.index');
    Route::get('/profile', [UserController::class, 'UserProfile'])->name('user.profile');
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('user.dashboard');
    Route::get('/user/changePassword', [UserController::class, 'UserChangePassword'])->name('user.changepassword');
    Route::post('/user/passwordUpdate', [UserController::class, 'PasswordUpdate'])->name('password.change.store');
    Route::post('/profile/store', [UserController::class, 'ProfileStore'])->name('profile.store');
    Route::get('/userlogout', [UserController::class, 'logout'])->name('user.logout');
    Route::get('/userbooking', [UserController::class, 'UserBooking'])->name('user.booking');
    Route::get('/userinvoice/{id}', [UserController::class, 'UserInvoice'])->name('user.invoice');
    // ENDUSERFE
});


Route::get('/admin/login', [AdminController::class, 'AdminLogin'])->name('admin.login')->middleware('guest');
//adminmidderware
Route::middleware(['auth', 'roles:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'AdminDashboard'])->name('admin.dashboard');
    Route::post('/admin/logout', [AdminController::class, 'AdminLogout'])->name('admin.logout');
    Route::get('/admin/profile', [AdminController::class, 'AdminProfile'])->name('admin.profile');
    Route::get('/admin/changepassword', [AdminController::class, 'AdminChangePassword'])->name('admin.change.password');
    Route::post('/admin/profile/store', [AdminController::class, 'AdminProfileStore'])->name('admin.profile.store');
    Route::post('/admin/updatepass', [AdminController::class, 'AdminPassUpdate'])->name('admin.pass.update');
});
//endadminmidderware

//adminmidderware
Route::middleware(['auth', 'roles:admin', 'https'])->group(function () {

    //teamAllRoute
    Route::controller(TeamController::class)->group(function () {
        Route::get('/all/team', 'AllTeam')->name('all.team');
        Route::get('/add/team', 'AddTeam')->name('add.team');
        Route::post('/team/store', 'StoreTeam')->name('team.store');
        Route::get('/team/delete/{id}', 'DeleteTeam')->name('delete.team');
        Route::get('/edit/team/{id}', 'EditTeam')->name('edit.team');
        Route::post('/team/update', 'UpdateTeam')->name('team.update');
    });
    //endTeamAllRoute

    //AdminBookingList
    Route::controller(AdminBookingController::class)->group(function () {
        Route::get('/booking/list', 'BookingList')->name('booking.list');
        Route::get('/edit_booking/{id}', 'EditBooking')->name('booking.edit');
        Route::post('/update/status/{id}', 'UpdateStatusBooking')->name('update.booking.status');
        Route::post('/update/booking_date/{id}', 'UpdateDateBooking')->name('update.booking.date');
        Route::get('/assign.room/store/{booking_id},{room_number_id}', 'BookingRoomStore')->name('assign.room.store');
        Route::get('/assign.room/delete/{id}', 'DeleteAssign')->name('delete.room.assign');
        Route::get('/download/invoice/{id}', 'DownloadInvoice')->name('download.invoice');
        //ajax
        Route::get('/assign_room/{id}', 'AssignRoom')->name('assign_room');
    });
    //EndAdminBookingList

    //Roomlist
    Route::controller(RoomListController::class)->group(function () {

        Route::get('list_room/view', 'ViewRoomList')->name('view.room.list');
        Route::get('list_room/add', 'AddRoomList')->name('add.room.list');
        Route::post('list_room/store', 'StoreRoomList')->name('store.room.list');
    });
    //END ROOM LIST

    //SMTP_SETTING
    Route::controller(SettingController::class)->group(function () {

        Route::get('smtp/setting', 'SmtpSetting')->name('smtp.setting');
        Route::post('smtp/update', 'SmtpUpdate')->name('smtp.update');
    });
    //TESTIMONICAL
    Route::controller(TestimonicalController::class)->group(function () {
        Route::get('testimonnical/all', 'AllTestimonical')->name('all.testimonial');
        Route::get('testimonnical/add', 'AddTestimonical')->name('add.testimonial');
        Route::post('testimonnical/store', 'StoreTestimonical')->name('store.testimonial');
        Route::get('testimonnical/edit/{id}', 'EditTestimonical')->name('edit.testimonial');
        Route::get('testimonnical/delete/{id}', 'DeleteTestimonial')->name('delete.testimonial');
        Route::post('testimonnical/update', 'UpdateTestimonial')->name('update.testimonial');
    });
    //TESTIMONICAL END

    /// Blog Post All Route 
    Route::controller(BlogController::class)->group(function () {
        Route::get('category/blog', 'BlogCategory')->name('blog.category');
        Route::post('category/blog/store', 'StoreBlogCategory')->name('store.blog.category');
        Route::post('category/blog/update', 'UpdateBlogCategory')->name('update.blog.category');
        Route::get('/edit/blog/category/{id}', 'EditBlogCategory');
        Route::get('/delete/blog/category/{id}', 'DeleteBlogCategory')->name('delete.blog.category');
    });

    Route::controller(BlogController::class)->group(function () {
        Route::get('/all/blog/post', 'AllBlogPost')->name('all.blog.post');
        Route::get('/add/blog/post', 'AddBlogPost')->name('add.blog.post');
        Route::post('/store/blog/post', 'StoreBlogPost')->name('store.blog.post');
        Route::get('/edit/blog/post/{id}', 'EditBlogPost')->name('edit.blog.post');
        Route::post('/update/blog/post', 'UpdateBlogPost')->name('update.blog.post');
        Route::get('/delete/blog/post/{id}', 'DeleteBlogPost')->name('delete.blog.post');
    });

    // END BLOGPOST ALL ROUTE

    //COMMENT CONTROLLER
    Route::controller(CommentController::class)->group(function () {

        Route::get('/comment/all', 'AllComment')->name('comment.all');
        Route::post('/comment/update/status', 'UpdateCommentStatus')->name('update.comment.status');
    });
    //END COMMENT CONTROLLER

    //REPORTCONTROLLER
    Route::controller(ReportController::class)->group(function () {
        Route::get('/booking/report', 'BookingReport')->name('booking.report');
        Route::post('/search-by-date', 'SearchByDate')->name('search-by-date');
    });
    //END REPORTCONTROLLER

    /// Site Setting All Route 
    Route::controller(SiteController::class)->group(function () {

        Route::get('/site/setting', 'SiteSetting')->name('site.setting');
        Route::post('/site/update', 'SiteUpdate')->name('site.update');
    });
    ///END Site Setting All Route 

    //GALLERY CONTROLLER
    Route::controller(GalleryController::class)->group(function () {

        Route::get('/all/gallery', 'AllGallery')->name('all.gallery');
        Route::get('/add/gallery', 'AddGallery')->name('add.gallery');
        Route::post('/store/gallery', 'StoreGallery')->name('store.gallery');
        Route::get('/edit/gallery/{id}', 'EditGallery')->name('edit.gallery');
        Route::post('/update/gallery', 'UpdateGallery')->name('update.gallery');
        Route::get('/delete/gallery/{id}', 'DeleteGallery')->name('delete.gallery');
        Route::post('/delete/gallery/multiple', 'DeleteGalleryMultiple')->name('delete.gallery.multiple');
    });
    //END GALLERY CONTROLLER

    //PERMISSION
    Route::controller(RoleController::class)->group(function () {

        Route::get('/all/permission', 'AllPermission')->name('all.permission');
        Route::get('/add/permission', 'AddPermission')->name('add.permission');
        Route::post('/store/permission', 'StorePermission')->name('store.permission');
        Route::get('/edit/permission/{id}', 'EditPermission')->name('edit.permission');
        Route::post('/update/permission', 'UpdatePermission')->name('update.permission');
        Route::get('/delete/permission/{id}', 'DeletePermission')->name('delete.permission');

        Route::get('/import/permission', 'ImportPermission')->name('import.permission');
        Route::get('/export', 'Export')->name('export');
        Route::post('/import', 'Import')->name('import');
    });
    //ENDPERMISSION

    /// Role All Route 
    Route::controller(RoleController::class)->group(function () {

        Route::get('/all/roles', 'AllRoles')->name('all.roles');
        Route::get('/add/roles', 'AddRoles')->name('add.roles');
        Route::post('/store/roles', 'StoreRoles')->name('store.roles');
        Route::get('/edit/roles/{id}', 'EditRoles')->name('edit.roles');
        Route::post('/update/roles', 'UpdateRoles')->name('update.roles');
        Route::get('/delete/roles/{id}', 'DeleteRoles')->name('delete.roles');

        Route::get('/add/roles/permission', 'AddRolesPermission')->name('add.roles.permission');
        Route::post('/store/roles/permission', 'RolePermissionStore')->name('roles.permission.store');
        Route::get('/all/roles/permission', 'AllRolesPermission')->name('all.roles.permission');
        Route::get('/admin/edit/roles/{id}', 'AdminEditRoles')->name('admin.edit.roles');
        Route::post('/admin/update/roles/{id}', 'AdminRolesUpdate')->name('admin.roles.update');
        Route::get('/admin/delete/roles/{id}', 'AdminRolesDelete')->name('admin.roles.delete');
    });


    /// Admin User All Route 
    Route::controller(AdminController::class)->group(function () {
        Route::get('/all/admin', 'AllAdmin')->name('all.admin');
        Route::get('/add/admin', 'AddAdmin')->name('add.admin');
        Route::post('/store/admin', 'StoreAdmin')->name('store.admin');
        Route::get('/edit/admin/{id}', 'EditAdmin')->name('edit.admin');
        Route::post('/update/admin/{id}', 'UpdateAdmin')->name('update.admin');
        Route::get('/delete/admin/{id}', 'DeleteAdmin')->name('delete.admin');
    });

    /// RoomType All Route 
Route::controller(RoomTypeController::class)->group(function () {
    Route::get('/room/type/list', 'RoomTypeList')->name('room.type.list');
    Route::get('/room/type/add', 'RoomTypeAdd')->name('room.type.add');
    Route::get('/room/type/delete/{id}', 'RoomTypeDelete')->name('room.type.delete');
    Route::post('/room/type/store', 'RoomTypeStore')->name('room.type.store');
});

/// EndRoomType All Route 

// BOOK_AREA
Route::controller(BookareaController::class)->group(function () {

    Route::get('/book/area', 'BookArea')->name('book.area');
    Route::post('/book/area/update', 'BookAreaUpdate')->name('book.area.update');
});
// END_ BOOK_AREA

// ROOM ALL ROUTE
Route::controller(RoomController::class)->group(function () {

    Route::get('/room/edit/{id}', 'EditRoom')->name('room.edit');
    Route::get('/room/delete/{id}', 'DeleteRoom')->name('room.delete');
    Route::post('/room/update/{id}', 'UpdateRoom')->name('update.room');
    Route::get('/multi/image/delete/{id}', 'MultiImageDelete')->name('multi.image.delete');
    Route::post('/store/room/no/{id}', 'StoreRoomNumber')->name('store.room.no');
    Route::get('/edit/roomno/{id}', 'EditRoomNumber')->name('edit.roomno');
    Route::post('/update/roomno/{id}', 'UpdateRoomNumber')->name('update.roomno');
    Route::get('/delete/roomno/{id}', 'DeleteRoomNumber')->name('delete.roomno');
});
//end ROOM ALL ROUTE

});
// endadminmidderware





/// Frontend Blog  All Route 
Route::controller(BlogController::class)->group(function () {
    Route::get('/blog/details/{slug}', 'BlogDetails')->name('blog.detail');
    Route::get('/blog/cat/{id}', 'BlogCatList')->name('blog.cat.list');
    Route::get('/blog', 'BlogList')->name('blog.list');
});
///end  Frontend Blog  All Route 

//Comment 
Route::controller(CommentController::class)->group(function () {
    Route::post('comment/store', 'CommentStore')->name('comment.store');
});
//END COMMENT







// Frontend ROOM 
Route::controller(FrontendRoomController::class)->group(function () {

    Route::get('/rooms', 'AllRoomFrontend')->name('froom.all');
    Route::get('/room/detail/{id}', 'RoomDetail')->name('room.detail');
    Route::post('bookings', 'BookingSearch')->name('booking.search');
    Route::get('/room/search/detail/{id}', 'SearchRoomDetails')->name('search_room_details');
    //ajax
    Route::get('/check_room/availability/', 'CheckRoomAvailability')->name('check.room.availability');
});

/// Frontend Gallery 
Route::controller(GalleryController::class)->group(function () {

    Route::get('/gallery', 'ShowGallery')->name('show.gallery');
    Route::get('/contact', 'ContactUs')->name('contact.us');
    Route::post('/store/contact', 'StoreContactUs')->name('store.contact');
    // contact message admin view
    Route::get('/contact/message', 'AdminContactMessage')->name('contact.message')->middleware('roles:admin');
});



Route::middleware(['auth'])->group(function () {

    /// CHECKOUT ALL Route 
    Route::controller(BookingController::class)->group(function () {
        Route::get('/checkout/', 'Checkout')->name('checkout');
        Route::post('/booking/store/', 'UserBookingStore')->name('user.booking.store');
        Route::post('/checkout/store/', 'CheckoutStore')->name('checkout.store');

           Route::match(['get', 'post'],'/stripe_pay', [BookingController::class, 'stripe_pay'])->name('stripe_pay');
    });
    /// Notification All Route 
    Route::controller(BookingController::class)->group(function () {
        Route::post('/mark-notification-as-read/{notification}', 'MarkAsRead');
    });
    Route::prefix('tour')->name('tour.')->group(function () {
        Route::get('/', [BookingTourController::class, 'index'])->name('index');
        Route::post('/store', [BookingTourController::class, 'store'])->name('store');
        Route::get('/check_tour_avlb', [BookingTourController::class, 'CheckRoomAvailabilityTour'])->name('check.room.availability');
    });
});




require __DIR__ . '/auth.php';
