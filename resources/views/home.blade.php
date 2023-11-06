@extends('layouts.app')


@section('content')
<div class=" container home-container">
  
  <div class="d-flex justify-content-between align-items-center">
    <button type="button" class="btn btn-primary btn-add-new"><i class="fa-solid fa-file-circle-plus"></i></button>
    <form class="search-form" method="GET" action="{{ route('home') }}">
      <input class="form-control search-input" type="search" name="search" placeholder="Search" aria-label="Search" value="{{$search}}">
      <button class="btn btn-outline-primary search-button" type="submit"><i class="fa-solid fa-magnifying-glass fa-lg"></i></button>
  </form>
  
  </div>

  <table class="table custom-table">
      <thead>
          <tr class="table-primary">
            <th>
              <div class="d-flex justify-content-between">
                Job Title
                <div>
                  <a href="{{ route('home', ['sort' => 'job_title', 'direction' => ($sortColumn === 'job_title' && $sortDirection === 'asc') ? 'desc' : 'asc',  'page' => $page]) }}">
                    <i class="fas fa-sort"></i>
                  </a>
                </div>
              </div>
            </th>
            <th>
              <div class="d-flex justify-content-between">
                Company Name
                <div>
                  <a href="{{ route('home', ['sort' => 'company_name', 'direction' => ($sortColumn === 'company_name' && $sortDirection === 'asc') ? 'desc' : 'asc',  'page' => $page]) }}">
                    <i class="fas fa-sort"></i>
                  </a>
                </div>
              </div>
            </th>
            <th>
              <div class="d-flex justify-content-between">
                Application Date
                <div>
                  <a href="{{ route('home', ['sort' => 'application_date', 'direction' => ($sortColumn === 'application_date' && $sortDirection === 'asc') ? 'desc' : 'asc',  'page' => $page]) }}">
                    <i class="fas fa-sort"></i>
                  </a>
                </div>
              </div>
            </th>
            <th>
              <div class="d-flex justify-content-between">
                Application Deadline
                <div>
                  <a href="{{ route('home', ['sort' => 'application_deadline', 'direction' => ($sortColumn === 'application_deadline' && $sortDirection === 'asc') ? 'desc' : 'asc',  'page' => $page]) }}">
                    <i class="fas fa-sort"></i>
                  </a>
                </div>
              </div>
            </th>
            <th>
              <div class="d-flex justify-content-between">
                Status
                <div>
                  <a href="{{ route('home', ['sort' => 'status', 'direction' => ($sortColumn === 'status' && $sortDirection === 'asc') ? 'desc' : 'asc',  'page' => $page]) }}">
                    <i class="fas fa-sort"></i>
                  </a>
                </div>
              </div>
            </th>  
            <th>
              <div class="d-flex justify-content-between">
                Contact Information
                <div>
                  <a href="{{ route('home', ['sort' => 'contact_information', 'direction' => ($sortColumn === 'contact_information' && $sortDirection === 'asc') ? 'desc' : 'asc',  'page' => $page]) }}">
                    <i class="fas fa-sort"></i>
                  </a>
                </div>
              </div>
            </th>
            <th>
              <div class="d-flex justify-content-between">
                Notes and Comments
                <div>
                  <a href="{{ route('home', ['sort' => 'notes_or_comments', 'direction' => ($sortColumn === 'notes_or_comments' && $sortDirection === 'asc') ? 'desc' : 'asc',  'page' => $page]) }}">
                    <i class="fas fa-sort"></i>
                  </a>
                </div>
              </div>
            </th>
            <th>
              <div class="d-flex justify-content-between">
                Submitted Documents
              </div>
            </th>
            <th>
              <div class="d-flex justify-content-between">
                Actions
                
              </div>
            </th>
          </tr>
      </thead>
      <tbody>
        @forelse ($jobs as $job)
          <tr>
              <td>
                @if (strlen($job->job_title) > 20)
                    {{ substr($job->job_title, 0, 20) }}...
                @else
                    {{ $job->job_title }}
                @endif
              </td>
              <td>
                @if (strlen($job->company_name) > 20)
                    {{ substr($job->company_name, 0, 20) }}...
                @else
                    {{ $job->company_name }}
                @endif
              </td>
              <td>
                @if (strlen($job->application_date) > 20)
                    {{ substr($job->application_date, 0, 20) }}...
                @else
                    {{ $job->application_date }}
                @endif
              </td>
              <td>
                @if (strlen($job->application_deadline) > 20)
                    {{ substr($job->application_deadline, 0, 20) }}...
                @else
                    {{ $job->application_deadline }}
                @endif
              </td>
              <td>
                @php
                    $statusColor = '';
                    if ($job->status === 'approved') {
                        $statusColor = 'bg-success';
                    } elseif ($job->status === 'pending') {
                        $statusColor = 'bg-warning';
                    } elseif ($job->status === 'rejected') {
                        $statusColor = 'bg-danger';
                    }
                @endphp
                <span class="badge {{ $statusColor }}">{{ $job->status }}</span>
            </td>
              <td>{{$job->contact_information}}</td>
              <td>
                @if (strlen($job->notes_or_comments) > 20)
                    {{ substr($job->notes_or_comments, 0, 20) }}...
                @else
                    {{ $job->notes_or_comments }}
                @endif
              </td>
                <td>
                  <div class="btn-group">
                      <button type="button" class="btn btn-primary btn-view-documents" data-id="{{$job->id}}"><i class="fa-solid fa-eye"></i> View Docs</button>
                  </div>
                </td>
              <td>
                  <div class="btn-group">
                      <button type="button" class="btn btn-primary btn-view-job" data-id="{{$job->id}}"><i class="fa-solid fa-eye"></i></button>
                      <button type="button" class="btn btn-secondary btn-edit-job" data-id="{{$job->id}}"><i class="fa-regular fa-pen-to-square"></i></button>
                      <button type="button" class="btn btn-danger btn-archive-job" data-id="{{$job->id}}"><i class="fa-solid fa-trash"></i></button>
                  </div>
              </td>
          </tr>
        @empty
          <tr>
              <td colspan="8">No results found.</td>
          </tr>
        @endforelse

      </tbody>
  </table>
  <div class="d-flex justify-content-center">
    {{ $jobs->appends(['sort' => $sortColumn, 'direction' => $sortDirection])->links('vendor.pagination.custom-pagination') }}
</div>

</div>

@include('components.jobs.create-job-modal')
@include('components.jobs.view-job-modal')
@include('components.jobs.edit-job-modal')
@include('components.jobs.archive-job-modal')

<script>
  $(document).ready(function () {
  });
</script>

@endsection
