# easybib/generator-processes

A collection of processes operating on iterators, usually implemented as generators

# Processes

## ElasticSearch::write
Uses Elastica to bulk write arrays of documents to Elasticsearch using an
Elastica type. You may use `Elastica::bindWrite($type)` to retrieve a method
which accepts only an iterator of document sets.
