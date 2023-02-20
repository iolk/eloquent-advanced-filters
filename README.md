High level grammar (todo)

```
filters: { TOP_EXPR }

TOP_EXPR:
   $and: [ ARRAY_EXPR ], AND_EXPR
 | $or: [ ARRAY_EXPR ], OR_EXPR
 | EXPR

EXPR:
  EXPR, EXPR
| RELATION { EXPR }
| ATTRIBUTE { OPERATOR: VALUE }

OR_EXPR:
   OR_EXPR, OR_EXPR
 | $and: [ ARRAY_EXPR ], EXPR
 | EXPR

AND_EXPR:
   AND_EXPR, AND_EXPR
 | $or: [ ARRAY_EXPR ], EXPR
 | EXPR

ARRAY_EXPR:
  { EXPR }
| { EXPR }, ARRAY_EXPR

ATTRIBUTE: (Any attribute of the entity filtered)

RELATION: (Any relationship of the entity filtered)

OPERATOR: (Any comparison or additional operator as defined in the tables above)

VALUE: (Any JSON supported type, so boolean, string, or number)
```
