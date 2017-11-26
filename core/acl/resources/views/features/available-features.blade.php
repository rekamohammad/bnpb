<ul id='auto-checkboxes' data-name='foo' class="list-unstyled list-feature">
    <li id="mainNode">
        <input type="checkbox" id="expandCollapseAllTree">&nbsp;&nbsp;
        <label for="expandCollapseAllTree" class="label label-default allTree" onClick="return expandCollapseTree('mainNode');">{{ trans('acl::feature.all') }}</label>
        <ul>
            @foreach($featuresWithChildren[0] as $element)
                <li class="collapsed" id="node{{ $features[$element]->id }}">
                    <input type="checkbox" id="checkSelect{{ $features[$element]->id }}" name="features[]" value="{{ $features[$element]->id }}" @if (in_array($features[$element]->id, $active)) checked @endif>
                    <label for="checkSelect{{ $features[$element]->id }}" class="label label-warning" style="margin:5px;" onClick="return expandCollapseTree('node{{ $features[$element]->id }}');">{{ $features[$element]->name }}</label>
                    @if (isset($featuresWithChildren[$element]))
                        <ul>
                            @foreach($featuresWithChildren[$element] as $subElements)
                                <li class="collapsed" id="node{{ $features[$subElements]->id }}">
                                    <input type="checkbox" id="checkSelect{{ $features[$subElements]->id }}" name="features[]" value="{{ $features[$subElements]->id }}" @if (in_array($features[$subElements]->id, $active))checked @endif>
                                    <label for="checkSelect{{ $features[$subElements]->id }}" class="label label-primary nameMargin" onClick='return expandCollapseTree("node{{ $features[$subElements]->id }}");'>{{ $features[$subElements]->name }}</label>
                                    @if (isset($featuresWithChildren[$subElements]))
                                        <ul>
                                            @foreach($featuresWithChildren[$subElements] as $subSubElements)
                                                <li class="collapsed" id="node{{ $features[$subSubElements]->id }}">
                                                    <input type="checkbox" id="checkSelect{{ $features[$subSubElements]->id }}" name="features[]" value="{{ $features[$subSubElements]->id }}" @if (in_array($features[$subSubElements]->id, $active))checked @endif>
                                                    <label for="checkSelect{{ $features[$subSubElements]->id }}" class="label label-success nameMargin" onClick='return expandCollapseTree("node{{ $features[$subSubElements]->id }}");'>{{ $features[$subSubElements]->name }}</label>
                                                    @if (isset($featuresWithChildren[$subSubElements]))
                                                        <ul>
                                                            @foreach($featuresWithChildren[$subSubElements] as $grandChildrenElements)
                                                                <li class="collapsed" id="node{{ $features[$grandChildrenElements]->id }}">
                                                                    <input type="checkbox" id="checkSelect{{ $features[$grandChildrenElements]->id }}" name="features[]" value="{{ $features[$grandChildrenElements]->id }}" @if (in_array($features[$grandChildrenElements]->id, $active))checked @endif>
                                                                    <label for="checkSelect{{ $features[$grandChildrenElements]->id }}" class="label label-danger nameMargin" onClick='return expandCollapseTree("node{{ $features[$grandChildrenElements]->id }}");'>{{ $features[$grandChildrenElements]->name }}</label>
                                                                    @if (isset($featuresWithChildren[$grandChildrenElements]))
                                                                        <ul>
                                                                            @foreach($featuresWithChildren[$grandChildrenElements] as $greatGrandChildrenElements)
                                                                                <li class="collapsed" id="node{{ $features[$grandChildrenElements]->id }}">
                                                                                    <input type="checkbox" id="checkSelect{{ $features[$grandChildrenElements]->id }}" name="features[]" value="{{ $features[$grandChildrenElements]->id }}" @if (in_array($features[$grandChildrenElements]->id, $active))checked @endif>
                                                                                    <label for="checkSelect{{ $features[$grandChildrenElements]->id }}" class="label label-info nameMargin" onClick='return expandCollapseTree("node{{ $features[$grandChildrenElements]->id }}");'>{{ $features[$grandChildrenElements]->name }}</label>
                                                                                </li>
                                                                            @endforeach
                                                                        </ul>
                                                                    @endif
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    @endif
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </li>
            @endforeach
        </ul>
    </li>
</ul>

<div id="list_feature" data-features="{{ json_encode($features, JSON_HEX_APOS) }}"></div>
