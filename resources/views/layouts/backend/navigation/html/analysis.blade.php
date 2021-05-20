<li>
    <a href="#">
        Data Analysis <span class="fa arrow"></span>
    </a>

    <ul class="nav nav-second-level">
        <li>
            <a href="{{ route('center.woman.map') }}">
                <i class="fa fa-map-marker" aria-hidden="true"></i>
                Positive Cases In HeatMap
            </a>
        </li>
         <li>
            <a href="{{ route('analysis.gender') }}">
                <i class="fa fa-circle-o" aria-hidden="true"></i>
                Gender
            </a>
        </li>
        <li>
            <a href="{{ route('analysis.occupation') }}">
                <i class="fa fa-briefcase" aria-hidden="true"></i>
                Occupation
            </a>
        </li>
        <li>
            <a href="{{ route('analysis.antigen') }}">
                <i class="fa fa-flask" aria-hidden="true"></i>
                PCR / Antigen
            </a>
        </li>
    </ul>
</li>