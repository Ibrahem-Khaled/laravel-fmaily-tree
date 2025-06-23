@foreach ($people as $person)
    <div class="modal fade" id="showPersonModal{{ $person->id }}" tabindex="-1" role="dialog"
        aria-labelledby="showPersonModalLabel{{ $person->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="showPersonModalLabel{{ $person->id }}">تفاصيل الشخص:
                        {{ $person->full_name }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4 text-center">
                            <img src="{{ $person->photo_url ? asset('storage/' . $person->photo_url) : ($person->gender == 'male' ? asset('img/male-avatar.png') : asset('img/female-avatar.png')) }}"
                                alt="{{ $person->full_name }}" class="img-fluid rounded-circle mb-3"
                                style="max-height: 200px;">
                            <h4>{{ $person->full_name }}</h4>
                            <span class="badge badge-{{ $person->gender == 'male' ? 'primary' : 'pink' }}">
                                {{ $person->gender == 'male' ? 'ذكر' : 'أنثى' }}
                            </span>
                        </div>
                        <div class="col-md-8">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tr>
                                        <th width="30%">فترة الحياة</th>
                                        <td>{{ $person->life_span ?? 'غير معروف' }}</td>
                                    </tr>
                                    <tr>
                                        <th>العمر</th>
                                        <td>{{ $person->age ? $person->age . ' سنة' : 'غير معروف' }}</td>
                                    </tr>
                                    <tr>
                                        <th>المهنة</th>
                                        <td>{{ $person->occupation ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>المكان</th>
                                        <td>{{ $person->location ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>الأب/الأم</th>
                                        <td>
                                            @if ($person->parent)
                                                <a
                                                    href="{{ route('people.show', $person->parent->id) }}">{{ $person->parent->full_name }}</a>
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>عدد الأبناء</th>
                                        <td>{{ $person->children()->count() }}</td>
                                    </tr>
                                </table>
                            </div>

                            @if ($person->biography)
                                <div class="mt-4">
                                    <h5>سيرة ذاتية</h5>
                                    <p class="text-justify">{{ $person->biography }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
                </div>
            </div>
        </div>
    </div>
@endforeach
