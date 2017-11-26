<ul id='auto-checkboxes' data-name='foo' class="list-unstyled list-feature">
    <li id="mainNode">
        <input type="checkbox" id="expandCollapseAllTree">&nbsp;&nbsp;
        <label for="expandCollapseAllTree" class="label label-default allTree" onClick="return expandCollapseTree('mainNode');">{{ trans('acl::permissions.all') }}</label>
        <ul>
            @foreach($children[0] as $element)
                <li class="collapsed" id="node{{ $flags[$element]->id }}">
                    <input type="checkbox" id="checkSelect{{ $flags[$element]->id }}" name="flags[]" value="{{ $flags[$element]->id }}" @if (in_array($flags[$element]->flag, $active)) checked @endif>
                    <label for="checkSelect{{ $flags[$element]->id }}" class="label label-warning" style="margin: 5px;" onClick="return expandCollapseTree('node{{ $flags[$element]->id }}');">{{ $flags[$element]->name }}</label>
                    @if (isset($children[$element]))
                        <ul>
                            @foreach($children[$element] as $subElements)
                                <li class="collapsed" id="node{{ $flags[$subElements]->id }}">
                                    <input type="checkbox" id="checkSelect{{ $flags[$subElements]->id }}" name="flags[]" value="{{ $flags[$subElements]->id }}" @if (in_array($flags[$subElements]->flag, $active)) checked @endif>
                                    <label for="checkSelect{{ $flags[$subElements]->id }}" class="label label-primary nameMargin" onClick='return expandCollapseTree("node{{ $flags[$subElements]->id }}");'>{{ $flags[$subElements]->name }}</label>
                                    @if (isset($children[$subElements]))
                                        <ul>
                                            @foreach($children[$subElements] as $subSubElements)
                                                <li class="collapsed" id="node{{ $flags[$subSubElements]->id }}">
                                                    <input type="checkbox" id="checkSelect{{ $flags[$subSubElements]->id }}" name="flags[]" value="{{ $flags[$subSubElements]->id }}" @if (in_array($flags[$subSubElements]->flag, $active)) checked @endif>
                                                    <label for="checkSelect{{ $flags[$subSubElements]->id }}" class="label label-success nameMargin" onClick='return expandCollapseTree("node{{ $flags[$subSubElements]->id }}");'>{{ $flags[$subSubElements]->name }}</label>
                                                    @if(isset($children[$subSubElements]))
                                                        <ul>
                                                            @foreach($children[$subSubElements] as $grandChildrenElements)
                                                                <li class="collapsed" id="node{{ $flags[$grandChildrenElements]->id }}">
                                                                    <input type="checkbox" id="checkSelect{{ $flags[$grandChildrenElements]->id }}" name="flags[]" value="{{ $flags[$grandChildrenElements]->id }}" @if (in_array($flags[$grandChildrenElements]->flag, $active)) checked @endif>
                                                                    <label for="checkSelect{{ $flags[$grandChildrenElements]->id }}" class="label label-danger nameMargin" onClick='return expandCollapseTree("node{{ $flags[$grandChildrenElements]->id }}");'>{{ $flags[$grandChildrenElements]->name }}</label>
                                                                    @if(isset($children[$grandChildrenElements]))
                                                                        <ul>
                                                                            @foreach($children[$grandChildrenElements] as $greatGrandChildrenElements)
                                                                                <li class="collapsed" id="node{{ $flags[$grandChildrenElements]->id }}">
                                                                                    <input type="checkbox" id="checkSelect{{ $flags[$grandChildrenElements]->id }}" name="flags[]" value="{{ $flags[$grandChildrenElements]->id }}" @if (in_array($flags[$grandChildrenElements]->flag, $active)) checked @endif>
                                                                                    <label for="node{{ $flags[$grandChildrenElements]->id }}" class="label label-info nameMargin" onClick='return expandCollapseTree("node{{ $flags[$grandChildrenElements]->id }}");'>{{ $flags[$grandChildrenElements]->name }}</label>
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
