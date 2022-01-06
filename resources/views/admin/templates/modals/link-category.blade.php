<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title">Link <span>Categories</span></h5>
        <a href="#" class="close" data-dismiss="modal" aria-label="Close">
            <em class="icon ni ni-cross"></em>
        </a>
    </div>
    <div class="modal-body">
        <form method="post" action="{{ url('categories/link') }}" id="mail-form" class=" form-validate">
            @csrf

            <?php if(!isset($field_name) || !isset($field_id)) : ?>

                <div class="alert alert-danger">Can't link category no reference to link to, speak to your awesome developer</div>

            <?php else : ?>

            <?php
                $keys_to_ignore = [];
                if(isset($ignore) && $ignore->count())
                {
                    foreach ($ignore->get() as $key)
                    {
                        $keys_to_ignore[] = $key->id;
                    }
                }
            ?>

                <input type="hidden" name="{{ $field_name }}" value="{{ $field_id }}">
                <div class="form-group" >
                    <label class="form-label">Select category</label>
                    <div class="form-control-wrap">
                        <select class="form-select" data-search="on" name="new_link_id" required>
                            <?php if($categories = categories()) : ?>
                                <?php foreach($categories as $category) : ?>
                                    <?php
                                        if(!empty($keys_to_ignore))
                                        {
                                            if(in_array($category->id, $keys_to_ignore)) {
                                                continue;
                                            }
                                        }
                                    ?>

                                    <option value="{{ $category->id }}">{{ $category->title }}</option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <button type="submit" class="submit-btn btn btn-lg btn-primary">Save</button>
                </div>

            <?php endif; ?>



        </form>
    </div>
    {{--<div class="modal-footer bg-light">
        <span class="sub-text">Modal Footer Text</span>
    </div>--}}
</div>
