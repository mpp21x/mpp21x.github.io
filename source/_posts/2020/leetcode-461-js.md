---
title: LeetCode(461) - Hamming Distance(js/easy)
date: 2020-05-14 01:17:20
tags:
- leetcode
- javascript
- algorithm
img: /medias/page_images/2020/leetcode-461-js.png
categories: leetcode algorithms
---
* link：[461. Hamming Distance](https://leetcode.com/problems/hamming-distance/submissions/)
* difficulty：`easy`
* language：`javascript`

## Question

The Hamming distance between two integers is the number of positions at which the corresponding bits are different.

Given two integers x and y, calculate the Hamming distance.

Note:

0 ≤ `x`, `y` < 2³¹.

Example：

```
Input: x = 1, y = 4

Output: 2

Explanation:
1   (0 0 0 1)
4   (0 1 0 0)
       ↑   ↑

The above arrows point to positions where the corresponding bits are different.
```

### how to solve problem

We need to convert two variables decimal to binary , and count the different part.

So we can follow these step:

* 將兩個變數，從十進位轉二進位，而轉十進位至二進位，如果是透過人工計算，便是將整數一直除以 2 直到商數為 0，然後將最後除得商數，再加上每一次的餘數反轉排序後，即可得到二進位：個人記十進位轉二進位的口訣為： **最後商加反轉全餘數**。 
* 再來統計不同的位數，即可得出答案。


## How to implement algorithm in javascript

My Answer

```javascript
var hammingDistance = function (x, y) {
    var big = Math.max(x, y);
    var small = Math.min(x, y);
    big = (big).toString(2).split('');
    small = (small).toString(2).split('');
    var bigLength = big.length;
    var currentDifferentNum = bigLength - small.length;
    var distance = 0;
    for (var i = 0; i < bigLength; i++) {
        var isNotSameLength = currentDifferentNum !== 0;
        if (isNotSameLength) {
            currentDifferentNum--;
            small.unshift('q');
            if (big[i] !== '0') {
                distance++;
            }
            continue;
        }
        distance += big[i] !== small[i] ? 1 : 0;
    }
    return distance;
};
```

One line solution, the best answer from other person and more faster!

```javascript
var hammingDistance = function(x, y) {
    return (x ^ y).toString(2).replace(/0/g, '').length;
};
```

result


![The result](one.png)


`x ^ y`：`^` is Javascript bitwise operator,It can get a number which is binary to deciaml(if not same then add `1`) , can help us to identity the different positions.

* `15 & 9` result is `9` (`1111 & 1001 = 1001`)
* `15 | 9` result is `15` (`1111 | 1001 = 1111`)
* `15 ^ 9` result is `6` (`1111 ^ 1001 = 0110`)


`toString(2)`：decimal to number.

`replace(/0/g, '')`：to replace all the same position.

## How to verify algorithm

```javascript
console.log(hammingDistance(1, 4)); // should be 2
console.log(hammingDistance(3, 1)); // should be 1
console.log(hammingDistance(93, 73)); // should be 2
```

### GitHub 

[This is my code of practice link.](https://github.com/mpp21x/algorithm-exercise/tree/master/461.hammingDistance)

