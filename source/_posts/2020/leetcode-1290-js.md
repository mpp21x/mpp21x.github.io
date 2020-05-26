---
title: LeetCode(1290) - Convert Binary Number in a Linked List to Integer(js/easy)
date: 2020-05-13 10:13:02
tags:
- leetcode
- javascript
- algorithm
img: /medias/page_images/2020/leetcode-1290-js.png
categories: leetcode algorithms
---
* link：[Convert Binary Number in a Linked List to Integer](https://leetcode.com/problems/convert-binary-number-in-a-linked-list-to-integer/)
* difficulty：`easy`
* language：`javascript`

## Question

Given head which is a reference node to a singly-linked list. The value of each node in the linked list is either 0 or 1. The linked list holds the binary representation of a number.

Return the decimal value of the number in the linked list.

Example 1:

```
Input: head = [1,0,1]
Output: 5
Explanation: (101) in base 2 = (5) in base 10
```

Example 2:

```
Input: head = [0]
Output: 0
```

Example 3:

```
Input: head = [1]
Output: 1
```

Example 4:

```
Input: head = [1,0,0,1,0,0,1,1,1,0,0,0,0,0,0]
Output: 18880
```

Example 5:

```
Input: head = [0,0]
Output: 0
```


Constraints:

* The Linked List is not empty.
* Number of nodes will not exceed `30`.
* Each node's value is either `0` or `1`.

### Question Explanation & how to solve it

The variable `head` is a singly-linked list, and we just need to traverse the whole list, get each node value to a varable string called `binaryStr`.

And convert the `binaryStr` to Decimal, It's finished. 

Time complexity is `O(n²)`。


## How to implement algorithm in javascript

```javascript
var getDecimalValue = function (node, binaryStr = '') {
    while (node && node.val !== null) {
        binaryStr += node.val;
        node = node.next;
    }
    return parseInt(binaryStr, 2);
};
```

## How to verify algorithm

I write the code to simulate the leetcode input and verify the algorithm is fine.

Here is my code(use typescript).

```typescript
interface ListNode {
    val: number,
    next: ListNode | null,
}

class FakeNode implements ListNode {
    val: number;
    next: FakeNode = null;

    constructor(val: number) {
        this.val = val;
    }
}

function createNodes(nums: number[]) {
    let head;
    let lastOneNode;

    nums.forEach((num) => {
        if (!head) {
            head = new FakeNode(num);
            return;
        }
        if (!head.next) {
            head.next = new FakeNode(num);
            lastOneNode = head.next;
            return;
        }
        lastOneNode.next = new FakeNode(num);
        lastOneNode = lastOneNode.next;
    });

    return head;
}


var getDecimalValue = function (node: ListNode, binaryStr = '') {
    while (node && node.val !== null) {
        // console.log(`node.val :${node.val}`);
        binaryStr += node.val;
        node = node.next;
    }
    return parseInt(binaryStr, 2);
};
const head = createNodes([1, 0, 1]);
console.log('verify the `head` is truly same as the leetcode input `head`');
console.info(head.val);
console.info(head.next.val);
console.info(head.next.next.val);
console.log('\n----------------\n');
console.log(getDecimalValue(head));


```

### GitHub 

[This is my code of practice link](https://github.com/mpp21x/algorithm-exercise/tree/master/1290.convertBinaryNumberInLinkedListToInteger)。

