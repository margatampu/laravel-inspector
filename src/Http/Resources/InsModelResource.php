<?php

namespace MargaTampu\LaravelInspector\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class InsModelResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'               => $this->id,
            'ins_auth_id'      => $this->ins_auth_id,
            'inspectable_type' => $this->inspectable_type,
            'inspectable_id'   => $this->inspectable_id,
            'method'           => $this->method,
            'original'         => $this->original,
            'changes'          => $this->changes,
            'created_at'       => $this->created_at,
            'updated_at'       => $this->updated_at,
            'includes'         => [
                [
                    'diff' => $this->includeDifferences()
                ]
            ],
            'links'            => [
                [
                    'rel'  => 'self',
                    'href' => route('inspector::models.show', ['insModel' => $this->id]),
                    'type' => 'GET'
                ]
            ],
        ];
    }

    /**
     * Display difference between original and changes fields
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function includeDifferences()
    {
        $original = json_decode($this->original, true);
        $changes  = json_decode($this->changes, true);

        $diff = [];

        if ($changes && count($changes)) {
            // Flag to know if tag closed or not
            $tagClosed = true;

            // Use new data as format to original and changes data
            $diff['original'] = $changes;
            $diff['changes']  = $changes;

            // Tag info
            $diff['tags']['oldOpen']  = config('inspector.tags.old.open');
            $diff['tags']['oldClose'] = config('inspector.tags.old.close');
            $diff['tags']['newOpen']  = config('inspector.tags.new.open');
            $diff['tags']['newClose'] = config('inspector.tags.new.close');

            foreach ($changes as $column => $content) {
                $changeChars   = str_split($content);
                $originalChars = str_split($original[$column]);

                // Reset original value data because its old data come from changes
                $diff['original'][$column] = '';

                foreach ($originalChars as $index => $char) {
                    if (isset($changeChars[$index])) {
                        if ($changeChars[$index] !== $char) {
                            if ($index === 0 || ($originalChars[$index - 1] === $changeChars[$index - 1])) {
                                $tagClosed = false;

                                // Append open tag to original data when value is same
                                $diff['original'][$column] .= $diff['tags']['oldOpen'];
                            }
                        } elseif ($index >= 1) {
                            if ($originalChars[$index - 1] !== $changeChars[$index - 1]) {
                                $tagClosed = true;

                                // Append close tag to original data when value is difference
                                $diff['original'][$column] .= $diff['tags']['oldClose'];
                            }
                        }
                    }

                    // Return data by character
                    $diff['original'][$column] .= htmlspecialchars($char);
                }

                if (!$tagClosed) {
                    $tagClosed        = true;

                    // Append close tag to original data when it is end of loop
                    $diff['original'][$column] .= $diff['tags']['oldClose'];
                }

                foreach ($changeChars as $index => $char) {
                    if (isset($originalChars[$index])) {
                        if ($originalChars[$index] !== $char) {
                            if ($index === 0 || ($originalChars[$index - 1] === $changeChars[$index - 1])) {
                                $tagClosed = false;

                                // Append open tag to changes data when value is same
                                $diff['changes'][$column] .= $diff['tags']['newOpen'];
                            }
                        } elseif ($index >= 1) {
                            if ($originalChars[$index - 1] !== $changeChars[$index - 1]) {
                                $tagClosed = true;

                                // Append close tag to changes data when value is difference
                                $diff['changes'][$column] .= $diff['tags']['newClose'];
                            }
                        }
                    }

                    // Return data by character
                    $diff['changes'][$column] .= htmlspecialchars($char);
                }

                if (!$tagClosed) {
                    $tagClosed = true;

                    // Append close tag to changes data when it is end of loop
                    $diff['changes'][$column] .= $diff['tags']['newClose'];
                }
            }
        } elseif ($original && count($original)) {
            $diff['original'] = $original;
        }

        return $diff;
    }
}
