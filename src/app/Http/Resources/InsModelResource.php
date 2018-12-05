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
                'diff' => $this->includeDifferences()
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
            $diff['originals'] = $changes;
            $diff['changes']   = $changes;

            foreach ($changes as $column => $content) {
                $changeChars   = str_split($content);
                $originalChars = str_split($original[$column]);

                // Reset original value data because its old data come from changes
                $diff['originals'][$column] = '';
                $diff['changes'][$column]   = '';

                foreach ($originalChars as $index => $char) {
                    if (isset($changeChars[$index])) {
                        if ($changeChars[$index] !== $char) {
                            if ($index === 0 || ($originalChars[$index - 1] === $changeChars[$index - 1])) {
                                $tagClosed = false;

                                // Append open tag to original data when value is same
                                $diff['originals'][$column] .= config('inspector.tags.old.open');
                            }
                        } elseif ($index >= 1) {
                            if ($originalChars[$index - 1] !== $changeChars[$index - 1]) {
                                $tagClosed = true;

                                // Append close tag to original data when value is difference
                                $diff['originals'][$column] .= config('inspector.tags.old.close');
                            }
                        }
                    }

                    // Return data by character
                    $diff['originals'][$column] .= htmlspecialchars($char);
                }

                if (!$tagClosed) {
                    $tagClosed        = true;

                    // Append close tag to original data when it is end of loop
                    $diff['originals'][$column] .= config('inspector.tags.old.close');
                }

                foreach ($changeChars as $index => $char) {
                    if (isset($originalChars[$index])) {
                        if ($originalChars[$index] !== $char) {
                            if ($index === 0 || ($originalChars[$index - 1] === $changeChars[$index - 1])) {
                                $tagClosed = false;

                                // Append open tag to changes data when value is same
                                $diff['changes'][$column] .= config('inspector.tags.new.open');
                            }
                        } elseif ($index >= 1) {
                            if ($originalChars[$index - 1] !== $changeChars[$index - 1]) {
                                $tagClosed = true;

                                // Append close tag to changes data when value is difference
                                $diff['changes'][$column] .= config('inspector.tags.new.close');
                            }
                        }
                    }

                    // Return data by character
                    $diff['changes'][$column] .= htmlspecialchars($char);
                }

                if (!$tagClosed) {
                    $tagClosed = true;

                    // Append close tag to changes data when it is end of loop
                    $diff['changes'][$column] .= config('inspector.tags.new.close');
                }
            }
        } elseif ($original && count($original)) {
            $diff['originals'] = $original;
        }

        return $diff;
    }
}
