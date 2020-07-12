<section>
    <ul>
        @foreach($projects as $project)
            <li>
                <h2>{{$project->title}}</h2>
                <p>{{$project->description}}</p>
            </li>
        @endforeach
    </ul>
</section>