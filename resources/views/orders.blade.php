<!doctype html>
<html>
    <head>
        <title>Orders</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    </head>
    <body>
        <div class="container-fluid">
            <div class="row text-center">
                <h2>Orders Page</h2>
            </div>
            <div class="row">
                <div class="container border border-dark mt-5">
                    <div class="row  text-center">
                        <h4>Create Order Form</h4>
                    </div>
                    <div class="row  mt-3">
                        <div class="col-5">
                            Key for Order:
                            <select id="new-order-key">
                                <option value="">Choose Key for Order</option>
                                @foreach ($keys as $key)
                                    <option value="{{ $key->id }}">{{ $key->vehicle_info }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-5">
                            Technician for Order:
                            <select id="new-order-tech">
                                <option value="">Choose Tech for Order</option>
                                @foreach ($techs as $tech)
                                    <option value="{{ $tech->id }}">{{ $tech->full_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-2">
                            <button class="btn btn-success mb-2" id="create-order-button">Create Order</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row d-none" id="update-row">
                <div class="container border border-dark mt-5">
                    <div class="row  text-center">
                        <h4>Update Order Form</h4>
                    </div>
                    <div class="row  mt-3">
                        <div class="col-5">
                            Key for Order:
                            <select id="update-order-key">
                                @foreach ($keys as $key)
                                    <option value="{{ $key->id }}">{{ $key->vehicle_info }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-5">
                            Technician for Order:
                            <select id="update-order-tech">
                                @foreach ($techs as $tech)
                                    <option value="{{ $tech->id }}">{{ $tech->full_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-2">
                            <button class="btn btn-success mb-2" id="update-order-button">Update Order</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="container mt-5 text-center">
                    <h3>Created Orders</h3>
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Key</th>
                                <th>Veichle</th>
                                <th>Technician</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id='orders-table-body'>
                            @foreach ($orders as $order)
                                <tr id="order-row-{{ $order->id }}">
                                    <td>{{ $order->id }}</td>
                                    <td>{{ $order->key->description }}</td>
                                    <td>
                                        {{ $order->key->vehicle()->exists() ? 'VIN #'.$order->key->vehicle->first()->vin : 'No Vehicle Assigned' }}
                                    </td>
                                    <td>{{ $order->technician->full_name }}</td>
                                    <td>
                                        <button class="btn btn-success update-order" data-order="{{ $order }}">Update Order</button>
                                        <button class="btn btn-danger delete-order" data-order-id="{{ $order->id }}">Delete Order</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </body>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.js"  integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>

    <script>
    $("document").ready(function() {
        let updateOrder;

        $('.update-order').click(function() {
            let order = $(this).data('order');
            $('#update-row').removeClass('d-none');
            $('#update-order-key').val(order.key_id);
            $('#update-order-tech').val(order.technician_id);
            updateOrder = order;
        });

        $('.delete-order').click(function() {
            let orderId = $(this).data('orderId');

            if (!$('#update-row').hasClass('d-none')) {
                $('#update-row').addClass('d-none');
            }

            $.ajax({
                url: '/api/orders/' + orderId,
                type: 'DELETE',
                success: function(result) {
                    $('#order-row-' + orderId).first().remove();
                    alert(result.message);
                },
                error: function(result) {
                    alert(result.responseJSON.error);
                }
            });
        });

        $('#update-order-button').click(function() {
            $('#update-row').addClass('d-none');

            $.ajax({
                url: '/api/orders/' + updateOrder.id,
                data: {
                    'key': $('#update-order-key').val(),
                    'tech': $('#update-order-tech').val(),
                },
                type: 'PUT',
                success: function(result) {
                    let order = result.order;
                    let updatedRow = $('#order-row-' + order.id).first();
                    updatedRow.empty();

                    updatedRow.append(
                        '<td>' + order.id +'</td>' +
                            '<td>' + order.key.description +'</td>' +
                            '<td>VIN #' + (order.key.vehicle ? order.key.vehicle.vin : 'No Vehicle Assigned') +'</td>' +
                            '<td>' + order.technician.full_name +'</td>' +
                            '<td>' +
                                '<button class="btn btn-success update-order" data-order="' + order + '"">Update Order</button> ' +
                                '<button class="btn btn-danger delete-order" data-order-id="' + order.id + '"">Delete Order</button>' +
                        '</td>'
                    );
                },
                error: function(result) {
                    alert(result.responseJSON.error);
                }
            });
        });

        $('#create-order-button').click(function() {
            let key = $('#new-order-key').val();
            let tech = $('#new-order-tech').val();

            if (!key || !tech) {
                alert('Must assign Key and Tech for new orders.');
                return
            }

            $.ajax({
                url: '/api/orders',
                data: {
                    'key': key,
                    'tech': tech,
                },
                type: 'POST',
                success: function(result) {
                    let order = result.order;

                    $('#orders-table-body').append(
                        '<tr class="order-row-' + order.id  +'">' +
                            '<td>' + order.id +'</td>' +
                            '<td>' + order.key.description +'</td>' +
                            '<td>VIN #' + (order.key.vehicle ? order.key.vehicle.vin : 'No Vehicle Assigned') +'</td>' +
                            '<td>' + order.technician.full_name +'</td>' +
                            '<td>' +
                                '<button class="btn btn-success update-order" data-order="' + order + '"">Update Order</button> ' +
                                '<button class="btn btn-danger delete-order" data-order-id="' + order.id + '"">Delete Order</button>' +
                            '</td>' +
                        '</tr>'
                    );
                },
                error: function(result) {
                    alert(result.responseJSON.error);
                }
            });
        });
    });
    </script>
</html>
