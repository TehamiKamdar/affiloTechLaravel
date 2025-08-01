<div class="row">
    <div class="col-lg-12">
        <div class="form-group {{ $errors->has('roles') ? 'has-error' : '' }}">
            <label for="roles" class="font-weight-bold text-black">Select Role</label>
            <select name="roles" id="roles" class="form-control" required>
                <option value="" selected disabled>Please Select</option>
                @foreach($roles as $id => $roles)
                    <option value="{{ $id }}" {{ (old('roles') == $id || isset($user) && $user->roles->contains($id)) ? 'selected' : '' }}>{{ $roles }}</option>
                @endforeach
            </select>
            @if($errors->has('roles'))
                <em class="invalid-feedback">
                    {{ $errors->first('roles') }}
                </em>
            @endif

        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-6">
        <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
            <label for="name" class="font-weight-bold text-black">Full Name</label>
            <input type="text" id="name" name="name" class="form-control" value="{{ old('name', isset($user) ? $user->name : '') }}" required>
            @if($errors->has('name'))
                <em class="invalid-feedback">
                    {{ $errors->first('name') }}
                </em>
            @endif

        </div>
    </div>
    <div class="col-lg-6">
        <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
            <label for="email" class="font-weight-bold text-black">Email</label>
            <input type="email" id="email" name="email" class="form-control" value="{{ old('email', isset($user) ? $user->email : '') }}" required>
            @if($errors->has('email'))
                <em class="invalid-feedback">
                    {{ $errors->first('email') }}
                </em>
            @endif

        </div>
    </div>
</div>


<div class="row">
    <div class="col-lg-6">
        <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
            <label for="password" class="font-weight-bold text-black">Password {!! isset($user->id) ? null : '<span class="text-danger">*</span>' !!}</label>
            <input type="password" id="password" name="password" class="form-control" {{ isset($user->id) ? null : 'required' }}>
            @if($errors->has('password'))
                <em class="invalid-feedback">
                    {{ $errors->first('password') }}
                </em>
            @endif
        </div>
    </div>
    <div class="col-lg-6">
        <div class="form-group {{ $errors->has('password_confirmation') ? 'has-error' : '' }}">
            <label for="confirm_password" class="font-weight-bold text-black">Confirm Password {!! isset($user->id) ? null : '<span class="text-danger">*</span>' !!}</label>
            <input type="password" id="confirm_password" name="password_confirmation" class="form-control" {{ isset($user->id) ? null : 'required' }}>
            @if($errors->has('password_confirmation'))
                <em class="invalid-feedback">
                    {{ $errors->first('password_confirmation') }}
                </em>
            @endif
        </div>
    </div>
</div>

<div>
    <input class="btn btn-primary" type="submit" value="Save">
</div>
