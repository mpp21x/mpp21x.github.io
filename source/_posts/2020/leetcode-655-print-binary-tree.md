---
title: Leetcode 655. Print Binary Tree
date: 2020-09-04 21:24:42
tags:
- leetcode
- javascript
- algorithm
- data-structure-binary-tree
img: /medias/page_images/2020/leetcode-655-print-binary-tree.png
categories: leetcode algorithms

---
* link：[655. Print Binary Tree](https://leetcode.com/problems/print-binary-tree//)
* difficulty：`medium`
* language：`javascript`

## 題目原文

Print a binary tree in an `m*n` 2D string array following these rules:

1. The row number `m` should be equal to the height of the given binary tree.
2. The column number n should always be an odd number.
3. The root node's value (in string format) should be put in the exactly middle of the first row it can be put. The column and the row where the root node belongs will separate the rest space into two parts (**left-bottom part and right-bottom part**). You should print the left subtree in the left-bottom part and print the right subtree in the right-bottom part. The left-bottom part and the right-bottom part should have the same size. Even if one subtree is none while the other is not, you don't need to print anything for the none subtree but still need to leave the space as large as that for the other subtree. However, if two subtrees are none, then you don't need to leave space for both of them.
4. Each unused space should contain an empty string "".
5. Print the subtrees following the same rules.

** Example 1
```
Input:
     1
    /
   2
Output:
[["", "1", ""],
 ["2", "", ""]]
```

** Example 2:

```
Input:
     1
    / \
   2   3
    \
     4
Output:
[["", "", "", "1", "", "", ""],
 ["", "2", "", "", "", "3", ""],
 ["", "", "4", "", "", "", ""]]
```

** Example 3:

```
Input:
      1
     / \
    2   5
   / 
  3 
 / 
4 
Output:

[["",  "",  "", "",  "", "", "", "1", "",  "",  "",  "",  "", "", ""]
 ["",  "",  "", "2", "", "", "", "",  "",  "",  "",  "5", "", "", ""]
 ["",  "3", "", "",  "", "", "", "",  "",  "",  "",  "",  "", "", ""]
 ["4", "",  "", "",  "", "", "", "",  "",  "",  "",  "",  "", "", ""]]
```

* 本文所提到的 **行** 代表直的由上往下， **列** 代表橫的由左往右。

```
  *    *
  *    *
  *    *
第1行 第2行  ...
```

```
* * * 第1列
* * * 第2列
...
```

### 題目解釋

給予一個 Binary Tree 資料結構的參數 `root`，，並要求回傳一個矩陣，其為充滿字串的二維陣列，規則如下：

1. 矩陣高度 `m` 為二元樹的高度
2. 寬必然為奇數
3. 二元樹上的數值，對應到矩陣中先將左右兩邊分一半，然後再放於中間，左右兩邊二元樹互相對稱
4. 空白的值使用 "" 來表示。

> 此二元樹不具備左邊節點必然大於根節點的特性

## 解題思路

根據題目描述，答案本身可以拆分為三個部分來看，只要算出這三個部分就可完成此題目：
 
 1. 如何算出矩陣高度？
 
 2. 如何算出矩陣寬度？
 
 3. 如何算出在二元樹的節點數值，對應到陣列中的哪一個位置？


### 如何算出矩陣高度？

題目規則表示矩陣高度 `m` 代表二元樹高度，換句話說只要透過深度優先搜尋即可求出 `m`。 

```typescript
const getHeight = (root= null) => {
        if (!root) return 0;

        return 1 + Math.max(getHeight(root.left), getHeight(root.right));
};
```

### 如何算出矩陣寬度？

矩陣寬度 `n` ，跟矩陣高度 `m` 符合一定規律，透過計算可得出公式 `n = (2ᵐ - 1)`

至此便可算出一個高度寬度皆符合答案的二維陣列了


### 如何算出在二元樹的節點數值，對應到陣列中的哪一個位置？

首先觀察，每一個 Binary Tree 節點都可以對應到矩陣的某一個位置，而這個位置又符合一個特性是：

* 位於相對的 **起始 index** 與 **結尾 index** 的中間。

這是什麼意思？以題目提供的 Example 2 為例，最上方的節點必然在矩陣列的中間，這個中間是怎麼算的？便是因為一開始的節點，其所擁有的 **起始 index** 在陣列中為 `0`  ，**結尾 index** 在陣列中的 index 為 `6` ， 這兩個數中間值為 `3`。

而只要往下一個節點，往左時 **起始 index** 為 `0` 不變， **結尾 index** 則變 `3`，往右時 **起始 index** 變為 `3` ， **結尾 index** 為 `6` 不變，後面都按照這個規律計算，即可完成此題。

---


## 實作 JavaScript

以下提供 TypeScript 範例。

```typescript
class TreeNode {
    val: number;
    left: TreeNode;
    right: TreeNode;

    constructor(val: number) {
        this.val = val;
    }
}

var printTree = function (root: TreeNode): string[][] {
    const getHeight = (root: TreeNode = null): number => {
        if (!root) return 0;

        return 1 + Math.max(getHeight(root.left), getHeight(root.right));
    };
    const createMartix = (height): string[][] => {
        const width = (2 ** height) - 1;
        return new Array(height)
            .fill('')
            .map(row => new Array(width).fill(''));
    };
    const put = (node: TreeNode, matrix: string[][], currentHeight: number, L, R) => {
        if (!node) return;

        const middle = ((R - L) / 2) + L;
        matrix[currentHeight][middle] = node.val.toString();

        put(node.left, matrix, currentHeight + 1, L, middle - 1);
        put(node.right, matrix, currentHeight + 1, middle + 1, R);
    };

    const height = getHeight(root);
    const matrix = createMartix(height);
    put(root, matrix, 0, 0, matrix[0].length - 1);

    return matrix;
};
```


## 其他

### GitHub 紀錄

此次練習被收錄在我的 GitHub [點我連結](https://github.com/mpp21x/algorithm-exercise/tree/master/655.%20Print%20Binary%20Tree)。

