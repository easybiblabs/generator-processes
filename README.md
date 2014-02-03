# easybib/generator-processes

A collection of processes operating on iterators, usually implemented as generators

[![Build Status](https://travis-ci.org/easybiblabs/generator-processes.png?branch=master)](https://travis-ci.org/easybiblabs/generator-processes)

# Processes

## ElasticSearch::write
Uses Elastica to bulk write arrays of documents to Elasticsearch using an
Elastica type. You may use `Elastica::bindWrite($type)` to retrieve a method
which accepts only an iterator of document sets.

## Transform::bulkify
Aggregates the input iterator into bulks.

## Transform::unbulkify
Flattens the input by a single level.
