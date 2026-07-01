<h2>New Enquiry Received</h2>

<p><strong>Name:</strong> {{ $enquiry->name }}</p>
<p><strong>Phone:</strong> {{ $enquiry->phone }}</p>
<p><strong>Email:</strong> {{ $enquiry->email }}</p>
<p><strong>Message:</strong><br> {{ $enquiry->message }}</p>

@if($service)
    <hr>
    <h3>Service Details</h3>
    <p><strong>Title:</strong> {{ $service->title }}</p>
    <p><strong>Country:</strong> {{ $service->country }}</p>
    <p><strong>Location:</strong> {{ $service->location }}</p>
    <p><strong>Duration:</strong> {{ $service->duration }}</p>
    <p><strong>Group Size:</strong> {{ $service->group_size }}</p>

@endif
