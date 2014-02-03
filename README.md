# easybib/generator-processes

A collection of processes operating on iterators, usually implemented as generators

[![Build Status](https://travis-ci.org/easybiblabs/generator-processes.png?branch=master)](https://travis-ci.org/easybiblabs/generator-processes)

# Processes

All processes are callables which operate on an input traversable (typically
an iterator) and return an iterator.

## Bulk adding Elastica documents
`\EasyBib\Process\Elastica::write` uses Elastica to bulk write arrays of
documents to Elasticsearch using an Elastica type. You may use
`Elastica::bindWrite($type)` to retrieve a method which accepts only an
iterator of document sets.

    use Easybib\Process\Elastica;

    $documentGroups = [
        [$doc1, $doc2, $doc3],
        [$doc4, $doc5],
    ];

    $outputIterator = Elastica::write($elasticaType, $documentGroups);
    // equivalent to
    $write = Elastica::bindWrite($elasticaType);
    $outputIterator = $write($documentGroups);

    // $documentGroups == iterator_to_array($outputIterator);


## Bulkify Transformation
`\EasyBib\Process\Transform::bulkify` aggregates the input iterator into arrays.

    use Easybib\Process\Transform;

    $items = [1, 2, 3, 4];

    $outputIterator = Transform::bulkify(2, $items);
    // equivalent to
    $bulkify = Transform::bindBulkify(2);
    $outputIterator = $bulkify($items);

    // [[1, 2], [3, 4]] == iterator_to_array($outputIterator)
    

## Unbulkify Transformation
Flattens the input by a single level, thus reversing a bulkify operation.

    use Easybib\Process\Transform;

    $bulks = [[1, 2], [3, 4], [5, ['some', 'array']]]];

    $outputIterator = Transform::unbulkify($bulks);

    // [1, 2, 3, 4, 5, ['some', 'array']] == iterator_to_array($outputIterator)
