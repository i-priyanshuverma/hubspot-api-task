<table class="table table-striped">
    <thead>

        <tr>
            <th scope="col">vid</th>
            <th scope="col"> First name </th>
            <th scope="col"> Last Modified </th>
            <th scope="col"> Last name </th>
        </tr>
    </thead>
    <tbody>
        @foreach($collection['contacts'] as $contact)

        <tr>
            <td>{{$contact->vid}}</td>
            @foreach($contact->properties as $property)
            @foreach($property as $entity)
            <td> {{$entity}} </td>
            @endforeach
            @endforeach
        </tr>

        @endforeach

    </tbody>
</table>